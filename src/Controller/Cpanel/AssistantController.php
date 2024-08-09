<?php

namespace App\Controller\Cpanel;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssistantController extends AbstractController
{
    /**
     * @Route("/cpanel/project/assistant", name="cpanel_project_assistant")
     */
    public function index(): Response
    {
        return $this->render('cpanel/assistant/index.html.twig');
    }
}
