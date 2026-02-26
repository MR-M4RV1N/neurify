<?php

namespace App\Controller\Cpanel;

use App\Repository\WordingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DownloadController extends AbstractController
{
    /**
     * @Route("/cpanel/project/download", name="app_cpanel_download")
     */
    public function index(WordingRepository $wordingRepository): Response
    {
        $lang = $this->getUser()->getLang();

        return $this->render('cpanel/download/index.html.twig', [
            'project_description' => $wordingRepository->findOneBy(['name' => 'project_description', 'lang' => $lang])->getText(),
        ]);
    }
}
