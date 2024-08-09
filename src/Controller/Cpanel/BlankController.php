<?php

namespace App\Controller\Cpanel;

use App\Entity\Blank;
use App\Form\BlankType;
use App\Repository\BlankRepository;
use App\Service\ImageRemovalService;
use App\Service\ImageUploadHandlerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("cpanel/sets/blanks")
 */
class BlankController extends AbstractController
{
    private $imageRemovalService;
    private $uploadHandler;
    public function __construct(ImageRemovalService $imageRemovalService, ImageUploadHandlerService $uploadHandler)
    {
        $this->imageRemovalService = $imageRemovalService;
        $this->uploadHandler = $uploadHandler;
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"}, name="app_blank_index", methods={"GET"})
     */
    public function index(BlankRepository $blankRepository, int $id): Response
    {
        $lang = $this->getUser()->getLang();

        return $this->render('cpanel/blank/index.html.twig', [
            'blanks' => $blankRepository->findBy([
                'type' => $id,
                'lang' => $lang
            ])
        ]);
    }

    /**
     * @Route("/new", name="app_blank_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine, Security $security, SluggerInterface $slugger): Response
    {
        $entityManager = $doctrine->getManager();
        $blank = new Blank();

        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания события.');
        }

        $form = $this->createForm(BlankType::class, $blank);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Устанавливаем директорию для загрузки изображений
            $this->uploadHandler->setUploadDirectory($this->getParameter('image_upload_directory_blanks'));
            // Обработка изображения
            $imageName = $this->uploadHandler->handleUpload($form, 'image');
            // Устанавливаем имя файла изображения
            $blank->setImage($imageName);

            // Устанавливаем язык
            $blank->setLang($this->getUser()->getLang());
            $entityManager->persist($blank);
            $entityManager->flush();

            return $this->redirectToRoute('app_blank_index', [
                'id' => $blank->getType()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/blank/new.html.twig', [
            'blank' => $blank,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_blank_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Blank $blank, BlankRepository $blankRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(BlankType::class, $blank);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Устанавливаем директорию для загрузки изображений
            $this->uploadHandler->setUploadDirectory($this->getParameter('image_upload_directory_blanks'));
            // Использование uploadHandler для обработки загруженного файла
            $newFilename = $this->uploadHandler->handleUpload($form, 'image');
            // Удаляем старый файл изображения
            $this->imageRemovalService->removeImage($blank->getImage(), $this->getParameter('image_upload_directory_blanks'));
            // Устанавливаем новое имя файла изображения
            $blank->setImage($newFilename);

            // Обновляем сущность
            $blankRepository->add($blank, true);

            return $this->redirectToRoute('app_blank_index', [
                'id' => $blank->getType()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/blank/edit.html.twig', [
            'blank' => $blank,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_blank_delete", methods={"POST"})
     */
    public function delete(Request $request, Blank $blank, BlankRepository $blankRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blank->getId(), $request->request->get('_token'))) {
            // Удаляем старый файл изображения
            $this->imageRemovalService->removeImage($blank->getImage(), $this->getParameter('image_upload_directory_blanks'));
            // Удаляем сущность
            $blankRepository->remove($blank, true);
        }

        return $this->redirectToRoute('app_blank_index', [
            'id' => $blank->getType()
        ], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/show/{id}", requirements={"id"="\d+"}, name="app_blank_show", methods={"GET"})
     */
    public function show(Blank $blank): Response
    {
        return $this->render('cpanel/blank/show.html.twig', [
            'blank' => $blank,
        ]);
    }
}
