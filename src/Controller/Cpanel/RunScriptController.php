<?php

namespace App\Controller\Cpanel;

use App\Repository\ExerciseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RunScriptController extends AbstractController
{
    /**
     * @Route("/cpanel/run/script", methods={"GET"})
    */
    public function runScript(ExerciseRepository $exerciseRepository): Response
    {
        $filesystem = new Filesystem();
        $items = $exerciseRepository->findAll();

        foreach ($items as $i) {
            $oldImage = $this->getParameter('image_upload_directory').'/'.$i->getImage();
            $newImage = $this->getParameter('image_upload_directory_exercises').'/'.$i->getImage();

            try {
                if ($filesystem->exists($oldImage)) {
                    $filesystem->copy($oldImage, $newImage, true);
                    $filesystem->remove($oldImage);
                }
            } catch (IOExceptionInterface $exception) {
                // Логирование ошибки или другая обработка исключения
                // Например: $this->logger->error("An error occurred while moving file: {$exception->getMessage()}");
            }
        }
        return $this->redirectToRoute('app_draft_index');
    }
}
