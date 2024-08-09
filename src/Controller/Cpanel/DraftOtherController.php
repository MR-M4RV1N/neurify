<?php

namespace App\Controller\Cpanel;

use App\Entity\Progress;
use App\Entity\Draft;
use App\Entity\Event;
use App\Entity\User;
use App\Service\ImageRemovalService;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class DraftOtherController extends AbstractController
{
    private $imageRemovalService;

    public function __construct(ImageRemovalService $imageRemovalService)
    {
        $this->imageRemovalService = $imageRemovalService;
    }

    /**
     * @Route("/cpanel/editor/draft/complete/{id}", requirements={"id"="\d+"}, name="app_draft_complete", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function completeItem(ManagerRegistry $doctrine, Request $request, $id, Security $security): Response
    {
        $draft = $doctrine->getRepository(Draft::class)->find($id);
        if (!$draft) {
            throw $this->createNotFoundException('No entity found for id '.$id);
        }

        $user = $security->getUser();
        $entityManager = $doctrine->getManager();
        $event= new Event();
        $event->setUser($user);
        $event->setTitle($draft->getTitle());
        $event->setDescription($draft->getDescription());
        $event->setDate(new \DateTime());
        $event->setImage($draft->getImage());
        // Находим старый файл изображения и копируем его в новую директорию image_upload_directory_events, после чего старый файл удаляем
        $oldImage = $this->getParameter('image_upload_directory_drafts').'/'.$draft->getImage();
        $newImage = $this->getParameter('image_upload_directory_events').'/'.$draft->getImage();
        copy($oldImage, $newImage);
        // Удаляем старый файл изображения
        $this->imageRemovalService->removeImage($draft->getImage(), $this->getParameter('image_upload_directory_drafts'));

        $event->setPublic(false);
        $event->setLang($user->getLang());
        $entityManager->persist($event);
        $entityManager->remove($draft);

        // Обновляем ячейку progress в таблице User
        $item = $entityManager->getRepository(User::class)->find($user);
        $progress = $item->getProgress() + 1;
        $item->setProgress($progress);
        $entityManager->persist($item);

        $entityManager->flush();

        return $this->redirectToRoute('app_draft_index');
    }
}
