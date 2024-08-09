<?php

namespace App\Controller\Cpanel;

use App\Entity\Exercise;
use App\Entity\ExerciseCompleted;
use App\Entity\User;
use App\Entity\Progress;
use App\Entity\Event;
use App\Form\ExerciseType;
use App\Repository\ExerciseRepository;
use App\Service\ImageRemovalService;
use App\Service\ImageUploadHandlerService;
use Doctrine\Persistence\ManagerRegistry;
use HTMLPurifier;
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

class ExerciseController extends AbstractController
{
    private $htmlPurifier;
    private $imageRemovalService;
    private $uploadHandler;

    public function __construct(HTMLPurifier $htmlPurifier, ImageRemovalService $imageRemovalService, ImageUploadHandlerService $uploadHandler)
    {
        $this->htmlPurifier = $htmlPurifier;
        $this->imageRemovalService = $imageRemovalService;
        $this->uploadHandler = $uploadHandler;
    }

    /**
     * @Route("/cpanel/sets/exercises/", requirements={"id"="\d+"}, name="app_exercise_index", methods={"GET"})
     */
    public function index(ExerciseRepository $exerciseRepository): Response
    {
        $lang = $this->getUser()->getLang();

        return $this->render('cpanel/exercise/index.html.twig', [
            'exercises' => $exerciseRepository->findBy([
                'lang' => $lang
            ])
        ]);
    }

    /**
     * @Route("/cpanel/sets/exercises/new", name="app_exercise_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine, Security $security): Response
    {
        $entityManager = $doctrine->getManager();
        $exercise = new Exercise();

        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания события.');
        }

        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Очистка HTML перед сохранением
            $dirtyHtml = $form->get('description')->getData();
            $cleanHtml = $this->htmlPurifier->purify($dirtyHtml);
            $exercise->setDescription($cleanHtml);
            $exercise->setLang($this->getUser()->getLang());

            // Устанавливаем директорию для загрузки изображений
            $this->uploadHandler->setUploadDirectory($this->getParameter('image_upload_directory_exercises'));
            // Обработка изображения
            $imageName = $this->uploadHandler->handleUpload($form, 'image');
            // Устанавливаем имя файла изображения
            $exercise->setImage($imageName);

            if ($form->isValid()) {
                $entityManager->persist($exercise);
                $entityManager->flush();

                return $this->redirectToRoute('app_exercise_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('cpanel/exercise/new.html.twig', [
            'exercise' => $exercise,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/cpanel/sets/exercises/show/{id}", requirements={"id"="\d+"}, name="app_exercise_show", methods={"GET"})
     */
    public function show(Exercise $exercise): Response
    {
        return $this->render('cpanel/exercise/show.html.twig', [
            'exercise' => $exercise,
        ]);
    }

    /**
     * @Route("/cpanel/sets/exercises/{id}/edit", name="app_exercise_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Exercise $exercise, ExerciseRepository $exerciseRepository): Response
    {
        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Очистка HTML перед сохранением
            $dirtyHtml = $form->get('description')->getData();
            $cleanHtml = $this->htmlPurifier->purify($dirtyHtml);
            $exercise->setDescription($cleanHtml);

            // Устанавливаем директорию для загрузки изображений
            $this->uploadHandler->setUploadDirectory($this->getParameter('image_upload_directory_exercises'));
            // Использование uploadHandler для обработки загруженного файла
            $newFilename = $this->uploadHandler->handleUpload($form, 'image');
            // Удаляем старый файл изображения
            $this->imageRemovalService->removeImage($exercise->getImage(), $this->getParameter('image_upload_directory_exercises'));
            // Устанавливаем новое имя файла изображения
            $exercise->setImage($newFilename);

            $exerciseRepository->add($exercise, true);

            return $this->redirectToRoute('app_exercise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/exercise/edit.html.twig', [
            'exercise' => $exercise,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/cpanel/sets/exercises/{id}", name="app_exercise_delete", methods={"POST"})
     */
    public function delete(Request $request, Exercise $exercise, ExerciseRepository $exerciseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exercise->getId(), $request->request->get('_token'))) {
            // Удаляем старый файл изображения
            $this->imageRemovalService->removeImage($exercise->getImage(), $this->getParameter('image_upload_directory_exercises'));
            // Удаляем сущность
            $exerciseRepository->remove($exercise, true);
        }

        return $this->redirectToRoute('app_exercise_index', [], Response::HTTP_SEE_OTHER);
    }

//    /**
//     * @Route("/copy/{id}", name="app_exercise_copy", methods={"GET", "POST"})
//     */
//    public function copy($id, ManagerRegistry $doctrine, Security $security): Response
//    {
//        $user = $security->getUser();
//        if (!$user) {
//            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания события.');
//        }
//
//        $exercise = $doctrine->getRepository(Exercise::class)->findOneBy(['id' => $id]);
//
//        $entityManager = $doctrine->getManager();
//        $event = new Event();
//        $event->setTitle($exercise->getTitle());
//        $event->setDescription($exercise->getDescription());
//        $event->setUser($doctrine->getRepository(User::class)->find($user));
//        $event->setDate(new \DateTime());
//
//        // Получаем исходный путь к файлу
//        $originalFilename = $exercise->getImage();
//        $imageUploadDirectory = $this->getParameter('image_upload_directory_exercises');
//        $sourcePath = $imageUploadDirectory . '/' . $originalFilename;
//
//        // Генерируем новое уникальное имя для копии файла
//        $filesystem = new Filesystem();
//        $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
//        $newFilename = uniqid().'.'.$extension;
//
//        // Путь для нового файла
//        $targetPath = $imageUploadDirectory . '/' . $newFilename;
//
//        // Копируем файл
//        try {
//            $filesystem->copy($sourcePath, $targetPath, true);
//            // Устанавливаем новый путь к файлу в событие
//            $event->setImage($newFilename);
//        } catch (FileException $e) {
//            // Обработка ошибки, если файл не может быть скопирован
//            $this->addFlash('danger', 'Произошла ошибка при копировании файла.');
//            return $this->redirectToRoute('app_exercise_index');
//        }
//
//        $entityManager->persist($event);
//        $entityManager->flush();
//
//        return $this->redirectToRoute('app_events_list');
//    }

    /**
     * @Route("/cpanel/sets/exercises/complete/{id}", name="app_exercise_complete", methods={"GET", "POST"})
     */
    public function complete(Exercise $exercise, ManagerRegistry $doctrine, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания события.');
        }

        $entityManager = $doctrine->getManager();
        $exerciseCompleted = new ExerciseCompleted();
        $exerciseCompleted->setExercise($exercise);
        $exerciseCompleted->setUser($user);
        $entityManager->persist($exerciseCompleted);
        $entityManager->flush();

        return $this->redirectToRoute('app_exercise_index');
    }

    /**
     * @Route("/cpanel/sets/exercises/ajax/check", name="check_exercise")
     */
    public function checkExercise(Request $request, ManagerRegistry $doctrine, Security $security): Response
    {
        $exerciseId = $request->query->get('id');
        $exercise = $doctrine->getRepository(Exercise::class)->find($exerciseId);
        $user = $user = $security->getUser();
        $exerciseCompleted = $doctrine->getRepository(ExerciseCompleted::class)->findBy(['exercise' => $exercise, 'user' => $user]);

        $result = false;
        if ($exerciseCompleted) {
            $result = true;
        }

        return $this->json(['result' => $result]);
    }
}
