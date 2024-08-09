<?php

namespace App\Controller\Cpanel;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndevController extends AbstractController
{
    /**
     * @Route("/cpanel/index", name="cpanel")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('app_events_list');
    }
}
