<?php

namespace App\Controller\Cpanel\Matrix\Editor\Items;

use App\Entity\Ensemble;
use App\Entity\MatrixItem;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\ImageUploadHandlerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UpdateController extends AbstractController
{
    /**
     * @Route("/cpanel/editor/item/edit/{id}", name="item.update", methods={"POST"})
     */
    public function updateItem(
        ManagerRegistry $doctrine,
        Request $request,
        ImageUploadHandlerService $imageUploadHandler,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $item = $entityManager->getRepository(MatrixItem::class)->find($id);

        if (!$item) {
            throw $this->createNotFoundException('Matrix item not found');
        }

        $item->setTitle($request->get('title'));
        $item->setDescription($request->get('description'));

        $file = $request->files->get('file');
        if ($file) {
            $filesystem = new Filesystem();

            // Удаление старого файла
            $oldFilename = $item->getFilename();
            if (!empty($oldFilename)) {
                $oldPath = $this->getParameter('items_images_directory') . '/' . $oldFilename;
                if ($filesystem->exists($oldPath)) {
                    try {
                        $filesystem->remove($oldPath);
                    } catch (\Exception $e) {
                        $this->addFlash('warning', 'Не удалось удалить старое изображение: ' . $e->getMessage());
                    }
                }
            }

            // Загрузка нового
            try {
                $imageUploadHandler->setUploadDirectory($this->getParameter('items_images_directory'));
                $newFilename = $imageUploadHandler->handleUploadFile($file);
                $item->setFilename($newFilename);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Ошибка загрузки изображения: ' . $e->getMessage());
            }
        }

        $entityManager->flush();

        return $this->redirectToRoute('editor.show', [
            'id' => $item->getMatrixMap()->getId()
        ]);
    }
}
