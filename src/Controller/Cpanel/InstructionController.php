<?php

namespace App\Controller\Cpanel;

use App\Entity\Plan;
use App\Entity\Training;
use App\Entity\Week;
use App\Repository\WordingRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InstructionController extends AbstractController
{
    /**
     * @Route("/cpanel/project/instruction", name="cpanel_instruction_index")
     */
    public function index(WordingRepository $wordingRepository): Response
    {
        $points = $wordingRepository->findBy(['name' => 'point', 'lang' => $this->getUser()->getLang()]);

        $arr = [];
        foreach($points as $p) {
            $arr[] = [
                'text' => $p->getText()
            ];
        }

        return $this->render('cpanel/instruction/index.html.twig', [
            'item' => $arr
        ]);
    }
}
