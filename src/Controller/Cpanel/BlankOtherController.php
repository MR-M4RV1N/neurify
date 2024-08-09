<?php

namespace App\Controller\Cpanel;

use App\Entity\Blank;
use App\Entity\User;
use App\Entity\Progress;
use App\Entity\Event;
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
 * @Route("cpanel/sets/blanks")
 */
class BlankOtherController extends AbstractController
{
    /**
     * @Route("/complete/{id}", name="app_blank_complete", methods={"GET", "POST"})
     */
    public function complete($id, ManagerRegistry $doctrine, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания события.');
        }

        $blank = $doctrine->getRepository(Blank::class)->findOneBy(['id' => $id]);

        $entityManager = $doctrine->getManager();
        $event = new Event();
        $event->setTitle($blank->getTitle());
        $event->setDescription($blank->getDescription());
        $event->setUser($doctrine->getRepository(User::class)->find($user));
        $event->setPublic(false);
        $event->setDate(new \DateTime());
        $event->setLang($this->getUser()->getLang());

        // Получаем исходный путь к файлу
        $originalFilename = $blank->getImage();
        $imageUploadDirectory = $this->getParameter('image_upload_directory_blanks');
        $sourcePath = $imageUploadDirectory . '/' . $originalFilename;

        // Генерируем новое уникальное имя для копии файла
        $filesystem = new Filesystem();
        $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
        $newFilename = uniqid().'.'.$extension;

        // Путь для нового файла
        $targetPath = $this->getParameter('image_upload_directory_events') . '/' . $newFilename;

        // Копируем файл
        try {
            $filesystem->copy($sourcePath, $targetPath, true);
            // Устанавливаем новый путь к файлу в событие
            $event->setImage($newFilename);
        } catch (FileException $e) {
            // Обработка ошибки, если файл не может быть скопирован
            $this->addFlash('danger', 'Произошла ошибка при копировании файла.');
            return $this->redirectToRoute('app_blank_index');
        }

        $entityManager->persist($event);

        // Обновляем ячейку progress в таблице User
        $item = $entityManager->getRepository(User::class)->find($user);
        $progress = $item->getProgress() + 1;
        $item->setProgress($progress);
        $entityManager->persist($item);

        $entityManager->flush();

        return $this->redirectToRoute('app_events_list');
    }
}
