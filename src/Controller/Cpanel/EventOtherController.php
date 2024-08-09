<?php

namespace App\Controller\Cpanel;

use App\Entity\Progress;
use App\Entity\Event;
use App\Entity\User;
use App\Entity\Wording;
use App\Form\EventType;
use App\Repository\ProgressRepository;
use App\Repository\EventRepository;
use App\Service\ArrayFromItemsService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/cpanel/editor/events")
 */
class EventOtherController extends AbstractController
{
    /**
     * @Route("/result", name="app_events_result")
     */
    public function result(ManagerRegistry $doctrine): Response
    {
        $progress = $doctrine->getRepository(Progress::class)->findOneBy(['user' => $this->getUser(), 'active' => true]);

        $items = $doctrine->getRepository(Wording::class)->findBy(['name' => 'result', 'lang' => $this->getUser()->getLang()]);
        $arr = [];
        foreach($items as $i) {
            $arr[] = [
                'text' => $i->getText()
            ];
        }

        return $this->render('cpanel/event/other/result.html.twig', [
            'progress' => $progress,
            'items' => $arr
        ]);
    }

    /**
     * @Route("/my_public_events", name="app_events_list_public", methods={"GET"})
     */
    public function listPublic(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findBy(['user' => $this->getUser(), 'public' => true]);

        return $this->render('cpanel/event/other/public.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * @Route("/to_public/{id}", name="app_event_to_public", requirements={"id"="\d+"})
     */
    public function toPublic(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $item = $entityManager->getRepository(Event::class)->find($id);
        $item->setPublic(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_events_list');
    }

    /**
     * @Route("/retract_public/{id}", name="app_event_retract_public", requirements={"id"="\d+"})
     */
    public function retractPublic(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $item = $entityManager->getRepository(Event::class)->find($id);
        $item->setPublic(false);
        $entityManager->flush();

        return $this->redirectToRoute('app_events_list');
    }
}
