<?php

namespace App\Controller\Client\Account;

use App\Entity\Draft;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends AbstractController
{
    /**
     * @Route("/client/progress/activity/api/statistics/{id}", name="cpanel_api_statistics")
     */
    public function index(User $user, EntityManagerInterface $em): JsonResponse
    {
        // Извлекаем данные и группируем их по месяцам для Event
        $eventSql = "SELECT DATE_FORMAT(date, '%Y-%m') AS month, COUNT(*) AS count
             FROM event
             WHERE user_id = :userId
               AND YEAR(date) = YEAR(CURDATE())
             GROUP BY month
             ORDER BY month";

        $eventStmt = $em->getConnection()->prepare($eventSql);
        $eventResultSet = $eventStmt->executeQuery(['userId' => $user->getId()]);
        $eventStatistics = $eventResultSet->fetchAllAssociative();

        // Извлекаем данные и группируем их по месяцам для Draft
        $draftSql = "SELECT DATE_FORMAT(date, '%Y-%m') AS month, COUNT(*) AS count
                     FROM draft
                     WHERE user_id = :userId
                     GROUP BY month
                     ORDER BY month";

        $draftStmt = $em->getConnection()->prepare($draftSql);
        $draftResultSet = $draftStmt->executeQuery(['userId' => $user->getId()]);
        $draftStatistics = $draftResultSet->fetchAllAssociative();

        // Обработка данных для всех месяцев
        $months = [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
        ];

        $eventsData = [];
        $draftsData = [];

        foreach ($months as $key => $month) {
            $eventsData[$key] = [
                'month' => $month,
                'count' => 0
            ];
            $draftsData[$key] = [
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

        foreach ($draftStatistics as $stat) {
            $monthKey = substr($stat['month'], -2);
            if (isset($draftsData[$monthKey])) {
                $draftsData[$monthKey]['count'] = (int)$stat['count'];
            }
        }

        return new JsonResponse([
            'events' => array_values($eventsData),
            'drafts' => array_values($draftsData)
        ]);
    }
}
