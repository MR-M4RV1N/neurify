<?php

namespace App\Controller\Cpanel;

use App\Entity\Draft;
use App\Entity\Event;
use App\Entity\JournalWeekComment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends AbstractController
{
    /**
     * @Route("/cpanel/progress/activity", name="cpanel_progress_activity")
     */
    public function activity(): Response
    {
        // Определяем пользователя
        $user = $this->getUser();
        // Находим в Event все записи, которые принадлежат текущему пользователю
        $events = $this->getDoctrine()->getRepository(Event::class)->findEventsForCurrentYearByUser($user->getId());

        // Группируем события по месяцам
        $eventsByMonth = [];
        foreach ($events as $event) {
            $month = $event->getDate()->format('F Y'); // Форматируем месяц и год
            if (!isset($eventsByMonth[$month])) {
                $eventsByMonth[$month] = [];
            }

            // Получаем первое изображение или задаём default.jpg
            $image = $event->getImages()->first() ? $event->getImages()->first()->getUrl() : 'default-min.jpg';

            $eventsByMonth[$month][] = [
                'event' => $event,
                'image' => $image,
            ];
        }

        // Находим в Draft все записи, которые принадлежат текущему пользователю
        $drafts = $this->getDoctrine()->getRepository(Draft::class)->findBy(['user' => $user], ['date' => 'DESC']);


        return $this->render('cpanel/activity/index.html.twig', [
            'eventsCount' => count($events),
            'draftsCount' => count($drafts),
            'eventsByMonth' => $eventsByMonth,
            'profile' => $user,
        ]);
    }

    /**
     * @Route("/cpanel/progress/download-events", name="download_events")
     */
    public function downloadEventsAsTxt(): Response
    {
        // Получение текущего пользователя
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Пользователь не авторизован');
        }

        // Получение событий текущего года
        $events = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findEventsForCurrentYearByUser($user->getId());

        // Генерация содержимого TXT-файла
        $txtContent = "Список событий за текущий год:\n";
        foreach ($events as $event) {
            $txtContent .= sprintf(
                "ID: %d\nTitle: %s\nDate: %s\nDescription: %s\nPublic: %s\n\n",
                $event->getId(),
                $event->getTitle(),
                $event->getDate()->format('Y-m-d'),
                $event->getDescription(),
                $event->isPublic() ? 'Yes' : 'No'
            );
        }

        // Создание HTTP-ответа
        $response = new Response($txtContent);
        $response->headers->set('Content-Type', 'text/plain; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="report.txt"');

        return $response;
    }

    /**
     * @Route("/cpanel/progress/activity/api/statistics/{id}", name="cpanel_api_statistics")
     */
    public function index(
        User $user,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $year = (int) $request->query->get('year', date('Y'));

        $eventSql = "
        SELECT DATE_FORMAT(date, '%Y-%m') AS month, COUNT(*) AS count
        FROM event
        WHERE user_id = :userId
          AND date >= :from
          AND date < :to
        GROUP BY month
        ORDER BY month
    ";

        $from = sprintf('%d-01-01', $year);
        $to   = sprintf('%d-01-01', $year + 1);

        $eventStmt = $em->getConnection()->prepare($eventSql);
        $eventResultSet = $eventStmt->executeQuery([
            'userId' => $user->getId(),
            'from'   => $from,
            'to'     => $to,
        ]);

        $eventStatistics = $eventResultSet->fetchAllAssociative();

        // Обработка данных для всех месяцев
        $months = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        ];

        $eventsData = [];

        foreach ($months as $key => $month) {
            $eventsData[$key] = [
                'month' => $month,
                'count' => 0
            ];
        }

        foreach ($eventStatistics as $stat) {
            $monthKey = substr($stat['month'], -2);
            if (isset($eventsData[$monthKey])) {
                $eventsData[$monthKey]['count'] = (int)$stat['count'];
            }
        }

        return new JsonResponse([
            'events' => array_values($eventsData),
        ]);
    }

    /**
     * @Route("/cpanel/progress/journal/api/weekly-score/{id}", name="cpanel_api_weekly_score")
     */
    public function weeklyScore(
        User $user,
        EntityManagerInterface $em
    ): JsonResponse {
        $rows = $em->getRepository(JournalWeekComment::class)
            ->createQueryBuilder('w')
            ->select('w.weekStart AS weekStart, w.score AS score')
            ->andWhere('w.user = :user')
            ->andWhere('w.aiGenerated = 1') // если нужно только AI-генерённое
            ->setParameter('user', $user)
            ->orderBy('w.weekStart', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->getArrayResult();

        // хотим слева-направо по времени (старые -> новые)
        $rows = array_reverse($rows);

        $data = [];
        foreach ($rows as $r) {
            // weekStart может быть DateTime или строка, нормализуем:
            $weekStart = $r['weekStart'] instanceof \DateTimeInterface
                ? $r['weekStart']->format('Y-m-d')
                : (string) $r['weekStart'];

            $data[] = [
                'label' => $weekStart,      // можно сделать короче: '12-18 Jan'
                'score' => (int) ($r['score'] ?? 0),
            ];
        }

        return new JsonResponse([
            'weeks' => $data,
            'max'   => 60, // 12 сфер * 5 (для подсказок/линий/процентов)
        ]);
    }
}
