<?php

namespace App\Controller\Cpanel\Events;

use App\Entity\Draft;
use App\Entity\Event;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Pagerfanta\Doctrine\ORM\QueryAdapter as PagerfantaAdapter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\View\TwitterBootstrap4View;

/**
 * @Route("cpanel/public")
 */
class PublicOtherController extends AbstractController
{
    /**
     * @Route("/copy_to_draft/{id}", name="app_event_copy_to_draft", requirements={"id"="\d+"})
     */
    public function copyToDraft(Event $event): Response
    {
        // Проверяем, что событие публичное
        if (!$event->isPublic()) {
            throw $this->createNotFoundException('The event does not exist');
        }
        // Копируем событие в сущность Draft, где user это текущий пользователь
        $draft = new Draft();
        $draft->setUser($this->getUser());
        $draft->setTitle($event->getTitle());
        $draft->setDescription($event->getDescription());
        $draft->setImage($event->getImage());

        // Если $event->getImage() не равно default.jpg, то по $event->getImage() получаем название файла, находим его в image_upload_directory_events и копируем его в image_upload_directory_drafts
        if ($event->getImage() !== 'default.jpg') {
            $filesystem = new Filesystem();
            $filesystem->copy($this->getParameter('image_upload_directory_events') . '/' . $event->getImage(), $this->getParameter('image_upload_directory_drafts') . '/' . $event->getImage());
        }

        // Сохраняем сущность Draft
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($draft);
        $entityManager->flush();

        return $this->redirectToRoute('app_draft_index');
    }
}
