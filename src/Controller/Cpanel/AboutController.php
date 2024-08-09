<?php

namespace App\Controller\Cpanel;

use App\Repository\WordingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    /**
     * @Route("/cpanel/project/about", name="app_cpanel_about")
     */
    public function index(WordingRepository $wordingRepository): Response
    {
        $lang = $this->getUser()->getLang();

        return $this->render('cpanel/about/index.html.twig', [
            'project_description' => $wordingRepository->findOneBy(['name' => 'project_description', 'lang' => $lang])->getText(),
            'about_project' => $wordingRepository->findOneBy(['name' => 'about_project', 'lang' => $lang])->getText(),
            'about_the_project' => $wordingRepository->findOneBy(['name' => 'about_the_project', 'lang' => $lang])->getText(),
            'results' => $wordingRepository->findOneBy(['name' => 'results', 'lang' => $lang])->getText(),
            'neuroplasticity' => $wordingRepository->findOneBy(['name' => 'neuroplasticity', 'lang' => $lang])->getText(),
            'share_results' => $wordingRepository->findOneBy(['name' => 'share_results', 'lang' => $lang])->getText(),
        ]);
    }
}
