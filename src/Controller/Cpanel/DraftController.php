<?php

namespace App\Controller\Cpanel;

use App\Entity\Draft;
use App\Form\DraftType;
use App\Repository\DraftRepository;
use App\Service\ImageRemovalService;
use App\Service\ImageUploadHandlerService;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class DraftController extends AbstractController
{
    private $imageRemovalService;
    private $uploadHandler;
    public function __construct(ImageRemovalService $imageRemovalService, ImageUploadHandlerService $uploadHandler)
    {
        $this->imageRemovalService = $imageRemovalService;
        $this->uploadHandler = $uploadHandler;
    }

    /**
     * @Route("/cpanel/editor/draft", name="app_draft_index", methods={"GET"})
     */
    public function list(Request $request, DraftRepository $draftRepository): Response
    {
        $queryBuilder = $draftRepository->createDraftsQueryBuilder($this->getUser());
        // Создаем адаптер для Pagerfanta
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(12); // Количество элементов на странице
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1)); // Текущая страница

        return $this->render('cpanel/draft/index.html.twig', [
            'drafts' => $pagerfanta
        ]);
    }

    /**
     * @Route("/cpanel/editor/draft/new", name="app_draft_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания события.');
        }

        $entityManager = $doctrine->getManager();
        $draft = new Draft();
        $draft->setUser($user);

        $form = $this->createForm(DraftType::class, $draft, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Устанавливаем директорию для загрузки изображений
            $this->uploadHandler->setUploadDirectory($this->getParameter('image_upload_directory_drafts'));
            // Обработка изображения
            $imageName = $this->uploadHandler->handleUpload($form, 'image');
            // Устанавливаем имя файла изображения
            $draft->setImage($imageName);
            $draft->setDate(new \DateTime());

            $entityManager->persist($draft);
            $entityManager->flush();

            return $this->redirectToRoute('app_draft_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/draft/new.html.twig', [
            'draft' => $draft,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/cpanel/editor/draft/{id}/edit", requirements={"id"="\d+"}, name="app_draft_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Draft $draft, DraftRepository $draftRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(DraftType::class, $draft, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Устанавливаем директорию для загрузки изображений
            $this->uploadHandler->setUploadDirectory($this->getParameter('image_upload_directory_drafts'));
            // Использование uploadHandler для обработки загруженного файла
            $newFilename = $this->uploadHandler->handleUpload($form, 'image');
            // Удаляем старый файл изображения
            $this->imageRemovalService->removeImage($draft->getImage(), $this->getParameter('image_upload_directory_drafts'));
            // Устанавливаем новое имя файла изображения
            $draft->setImage($newFilename);

            // Обновляем сущность
            $draftRepository->add($draft, true);

            return $this->redirectToRoute('app_draft_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/draft/edit.html.twig', [
            'draft' => $draft,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/cpanel/editor/draft/{id}", requirements={"id"="\d+"}, name="app_draft_delete", methods={"POST"})
     */
    public function delete(Request $request, Draft $draft, DraftRepository $draftRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$draft->getId(), $request->request->get('_token'))) {
            // Удаляем старый файл изображения
            $this->imageRemovalService->removeImage($draft->getImage(), $this->getParameter('image_upload_directory_drafts'));
            // Удаляем сущность
            $draftRepository->remove($draft, true);
        }

        return $this->redirectToRoute('app_draft_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/cpanel/editor/draft/{id}", name="app_draft_show", methods={"GET"})
     */
    public function open(Draft $draft): Response
    {
        return $this->render('cpanel/draft/show.html.twig', [
            'draft' => $draft,
        ]);
    }
}
