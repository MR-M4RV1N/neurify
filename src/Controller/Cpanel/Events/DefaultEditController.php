<?php

namespace App\Controller\Cpanel\Events;

use App\Entity\Ensemble;
use App\Entity\Progress;
use App\Entity\Event;
use App\Entity\User;
use App\Entity\Wording;
use App\Form\EventType;
use App\Repository\CommentRepository;
use App\Repository\EnsembleParticipantRepository;
use App\Repository\ProgressRepository;
use App\Repository\EventRepository;
use App\Service\ArrayFromItemsService;
use App\Service\ImageRemovalService;
use App\Service\ImageUploadHandlerService;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
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
 * @Route("/cpanel/default/events")
 */
class DefaultEditController extends AbstractController
{
    private $uploadHandler;
    public function __construct(ImageUploadHandlerService $uploadHandler)
    {
        $this->uploadHandler = $uploadHandler;
    }

    /**
     * @Route("/new", name="app_default_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine, Security $security, SluggerInterface $slugger): Response
    {
        $entityManager = $doctrine->getManager();
        $event = new Event();

        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания события.');
        }
        $event->setUser($user);
        $event->setPublic(false);
        $event->setPinned(0);
        $event->setLang($user->getLang());
        $event->setEnsemble(null);

        $form = $this->createForm(EventType::class, $event, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Устанавливаем директорию для загрузки изображений
            $this->uploadHandler->setUploadDirectory($this->getParameter('image_upload_directory_events'));
            // Обработка изображения
            $imageName = $this->uploadHandler->handleUpload($form, 'image');
            // Устанавливаем имя файла изображения
            $event->setImage($imageName);

            $entityManager->persist($event);

            // Обновляем ячейку progress в таблице User
            $item = $entityManager->getRepository(User::class)->find($user);
            $progress = $item->getProgress() + 1;
            $item->setProgress($progress);
            $entityManager->persist($item);

            $entityManager->flush();

            return $this->redirectToRoute('app_default_open', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/events/event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }
}
