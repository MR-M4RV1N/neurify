<?php

namespace App\Controller\Cpanel\Journal;

use App\Entity\JournalWeekComment;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\JournalWeekCommentRepository;
use App\Service\Journal\WeeklyLifeBalanceConsultingService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/cpanel/journal/week")
 */
class JournalWeekController extends AbstractController
{
    /**
     * Просмотр комментария недели
     *
     * @Route(
     *     "/{week}",
     *     name="journal_week_show",
     *     requirements={"week"="\d{4}-\d{2}-\d{2}"},
     *     methods={"GET"}
     * )
     */
    public function show(
        string $week,
        Security $security,
        JournalWeekCommentRepository $repository,
        EventRepository $eventRepository,
        EntityManagerInterface $em
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $weekStart = (new \DateTimeImmutable($week))
            ->modify('monday this week');

        $comment = $repository->findOneBy([
            'user' => $user,
            'weekStart' => $weekStart,
        ]);

        $weekStart = (new \DateTimeImmutable($week))
            ->modify('monday this week');
        $weekEnd = $weekStart->modify('+6 days');
        $events = $eventRepository->createQueryBuilder('e')
            ->andWhere('e.user = :user')
            ->andWhere('e.aiGenerated = false')
            ->andWhere('e.date BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('start', $weekStart->setTime(0, 0, 0))
            ->setParameter('end', $weekEnd->setTime(23, 59, 59))
            ->orderBy('e.date', 'ASC')
            ->getQuery()
            ->getResult();

        // Подсчёт символов в ежедневных комментариях
        $journalEntryRepo = $em->getRepository(\App\Entity\JournalEntry::class);
        $dailyEntries = $journalEntryRepo->createQueryBuilder('je')
            ->andWhere('je.user = :user')
            ->andWhere('je.date BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('start', $weekStart->setTime(0, 0, 0))
            ->setParameter('end', $weekEnd->setTime(23, 59, 59))
            ->getQuery()
            ->getResult();

        $totalCharacters = 0;
        foreach ($dailyEntries as $entry) {
            $totalCharacters += mb_strlen((string)$entry->getContent());
        }

        return $this->render('cpanel/journal/week_show.html.twig', [
            'weekStart' => $weekStart,
            'weekEnd'   => $weekStart->modify('+6 days'),
            'prevWeek'  => $weekStart->modify('-7 days'),
            'nextWeek'  => $weekStart->modify('+7 days'),
            'comment'   => $comment,
            'events'    => $events,
            'weekNumber' => (int) $weekStart->format('W'),
            'weekYear'  => (int) $weekStart->format('o'),
            'totalCharacters' => $totalCharacters,
        ]);
    }

    /**
     * Редактирование комментария недели
     *
     * @Route(
     *     "/{week}/edit",
     *     name="journal_week_edit",
     *     requirements={"week"="\d{4}-\d{2}-\d{2}"},
     *     methods={"GET","POST"}
     * )
     */
    public function edit(
        string $week,
        Request $request,
        Security $security,
        JournalWeekCommentRepository $repository,
        EntityManagerInterface $em
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $weekStart = (new \DateTimeImmutable($week))
            ->modify('monday this week');

        $comment = $repository->findOneBy([
            'user' => $user,
            'weekStart' => $weekStart,
        ]);

        if (!$comment) {
            $comment = new JournalWeekComment();
            $comment
                ->setUser($user)
                ->setWeekStart($weekStart);
        }

        if ($request->isMethod('POST')) {
            $content = trim((string) $request->request->get('content'));
            $title = trim((string) $request->request->get('title'));
            $comment->setContent($content);
            $comment->setTitle($title);

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('journal_week_show', [
                'week' => $weekStart->format('Y-m-d'),
            ]);
        }

        return $this->render('cpanel/journal/week_edit.html.twig', [
            'weekStart' => $weekStart,
            'weekEnd'   => $weekStart->modify('+6 days'),
            'comment'   => $comment,
        ]);
    }

    /**
     * Генерация AI-анализа недели
     *
     * @Route(
     *     "/journal/week/{week}/generate-ai",
     *     name="journal_week_generate_ai",
     *     requirements={"week"="\d{4}-\d{2}-\d{2}"},
     *     methods={"POST"}
     * )
     */
    public function generateWeeklyAi(
        string $week,
        Request $request,
        Security $security,
        EntityManagerInterface $em,
        WeeklyLifeBalanceConsultingService $aiService
    ): Response {
        // 1) CSRF
        if (!$this->isCsrfTokenValid('journal_week_generate_ai', (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        // 2) User
        /** @var User|null $user */
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        // 3) Parse week строго (и нормализуем время)
        $weekStart = \DateTimeImmutable::createFromFormat('Y-m-d', $week);
        if (!$weekStart) {
            throw $this->createNotFoundException('Invalid week format');
        }
        $weekStart = $weekStart->setTime(0, 0, 0);

        // 4) Find-or-create (без дублей по возможности)
        $repo = $em->getRepository(JournalWeekComment::class);

        /** @var JournalWeekComment|null $comment */
        $comment = $repo->findOneBy([
            'user'      => $user,
            'weekStart' => $weekStart,
        ]);

        if (!$comment) {
            $comment = (new JournalWeekComment())
                ->setUser($user)
                ->setWeekStart($weekStart);

            // persist сразу, чтобы сущность гарантированно отслеживалась UoW
            $em->persist($comment);
        }

        // 5) Проверка "неделя заполнена"
        if (!$aiService->supports($user, $weekStart)) {
            $this->addFlash('warning', 'Неделя заполнена не полностью');

            return $this->redirectToRoute('journal_week_show', [
                'week' => $week,
            ]);
        }

        // 6) Генерация + сохранение, с обработкой ошибок и возможного дубля
        try {
            $aiService->generate($comment);

            // Важно: контроллер владеет сохранением
            $em->flush();

            $this->addFlash('success', 'AI-анализ недели сгенерирован');
        } catch (UniqueConstraintViolationException $e) {
            // Если в БД есть UNIQUE(user_id, week_start) и случилась гонка —
            // перезагрузим существующую запись и попробуем ещё раз
            $em->clear();

            /** @var JournalWeekComment|null $comment */
            $comment = $repo->findOneBy([
                'user'      => $user,
                'weekStart' => $weekStart,
            ]);

            if ($comment) {
                try {
                    $aiService->generate($comment);
                    $em->flush();

                    $this->addFlash('success', 'AI-анализ недели сгенерирован');
                } catch (\Throwable $e2) {
                    $this->addFlash('danger', 'Не удалось сгенерировать анализ. Попробуйте позже.');
                }
            } else {
                $this->addFlash('danger', 'Не удалось создать запись недели. Попробуйте позже.');
            }
        } catch (\Throwable $e) {
            $this->addFlash('danger', 'Не удалось сгенерировать анализ. Попробуйте позже.');
        }

        return $this->redirectToRoute('journal_week_show', [
            'week' => $week,
        ]);
    }

    /**
     * Скачивание дневников недели в формате TXT
     *
     * @Route(
     *     "/{week}/export",
     *     name="journal_week_export",
     *     requirements={"week"="\d{4}-\d{2}-\d{2}"},
     *     methods={"GET"}
     * )
     */
    public function export(
        string                 $week,
        Security               $security,
        EntityManagerInterface $em
    ): Response {
        /** @var User|null $user */
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $weekStart = \DateTimeImmutable::createFromFormat('Y-m-d', $week);
        if (!$weekStart) {
            throw $this->createNotFoundException('Invalid week format');
        }
        $weekStart = $weekStart->modify('monday this week')->setTime(0, 0, 0);
        $weekEnd = $weekStart->modify('+6 days')->setTime(23, 59, 59);

        $journalEntryRepo = $em->getRepository(\App\Entity\JournalEntry::class);
        $dailyEntries = $journalEntryRepo->createQueryBuilder('je')
            ->andWhere('je.user = :user')
            ->andWhere('je.date BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('start', $weekStart)
            ->setParameter('end', $weekEnd)
            ->orderBy('je.date', 'ASC')
            ->getQuery()
            ->getResult();

        $content = "";
        $days = [
            1 => 'Понедельник (Mon)',
            2 => 'Вторник (Tue)',
            3 => 'Среда (Wed)',
            4 => 'Четверг (Thu)',
            5 => 'Пятница (Fri)',
            6 => 'Суббота (Sat)',
            7 => 'Воскресенье (Sun)',
        ];

        foreach ($dailyEntries as $entry) {
            $dayNum = (int)$entry->getDate()->format('N');
            $dayName = $days[$dayNum] ?? $entry->getDate()->format('l');
            $dateStr = $entry->getDate()->format('d.m.Y');

            $content .= "=== {$dayName} - {$dateStr} ===\n";
            $content .= $entry->getContent() . "\n\n";
        }

        if (empty($content)) {
            $content = "Записей за эту неделю не найдено.";
        }

        $fileName = sprintf('week_%s_%s.txt', $weekStart->format('W'), $weekStart->format('o'));

        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }
}
