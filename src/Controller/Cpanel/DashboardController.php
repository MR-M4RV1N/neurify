<?php

namespace App\Controller\Cpanel;

use App\Entity\Progress;
use App\Entity\Event;
use App\Entity\Level;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/cpanel/dashboard", name="app_cpanel_dashboard")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $totalEvents = $doctrine->getRepository(Event::class)->count(['user' => $this->getUser()]);

        // Вычисляем уровень
        $number = floor($totalEvents / 9);
        if($number > 81){
            $level = 9;
        }
        else{
            $level = $doctrine->getRepository(Level::class)->find($number);
        }

        $nextLavel = ($number + 1) * 9 - $totalEvents;

        return $this->render('cpanel/dashboard/index.html.twig', [
            'totalEvents' => $totalEvents,
            'nextLevel' => $nextLavel,
            'level' => $level,
            'levels' => $doctrine->getRepository(Level::class)->findAll(),
            'totalCategories' => $doctrine->getRepository(Progress::class)->count(['user' => $this->getUser()]),
            'selectedProgress' => $doctrine->getRepository(Progress::class)->findOneBy(['user' => $this->getUser(), 'active' => true])->getTitle()
        ]);
    }
}
