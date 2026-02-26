<?php

namespace App\Controller\Cpanel\Matrix\Editor\Map;

use App\Entity\Ensemble;
use App\Entity\MatrixItem;
use App\Entity\MatrixMap;
use App\Service\ImageUploadHandlerService;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UpdateController extends AbstractController
{
    /**
     * @Route("/cpanel/editor/map/edit/{id}", name="map.update", methods={"POST"})
     */
    public function updateMap(
        ManagerRegistry $doctrine,
        Request $request,
        int $id,
        ImageUploadHandlerService $imageUploadHandler
    ): Response {
        $entityManager = $doctrine->getManager();
        $map = $entityManager->getRepository(MatrixMap::class)->find($id);

        if (!$map) {
            throw $this->createNotFoundException('Matrix map not found');
        }

        $map->setName($request->get('name'));
        $map->setDescription($request->get('description'));
        $map->setEnsemble($request->get('ensemble'));
        $map->setPublic($request->get('public') == 1);
        $map->setPortfolio($request->get('portfolio') == 1);
        $map->setUpdatedAt(new \DateTimeImmutable());

        // Обновление связи с Ensemble
        $ensembleId = $request->get('category'); // или 'ensemble'
        if ($ensembleId && $ensembleId !== 'empty') {
            $ensemble = $doctrine->getRepository(Ensemble::class)->find($ensembleId);
            if ($ensemble) {
                $map->setEnsemble($ensemble);
            }
        }

        // Обработка изображения
        $file = $request->files->get('file');
        if ($file) {
            $filesystem = new Filesystem();

            // Удаление старого файла
            if (!empty($map->getFilename())) {
                $oldPath = $this->getParameter('maps_images_directory') . '/' . $map->getFilename();
                if ($filesystem->exists($oldPath)) {
                    try {
                        $filesystem->remove($oldPath);
                    } catch (\Exception $e) {
                        $this->addFlash('warning', 'Не удалось удалить старое изображение: ' . $e->getMessage());
                    }
                }
            }

            // Загрузка нового изображения
            try {
                $imageUploadHandler->setUploadDirectory($this->getParameter('maps_images_directory'));
                $newFilename = $imageUploadHandler->handleUploadFile($file);
                $map->setFilename($newFilename);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Ошибка загрузки изображения: ' . $e->getMessage());
            }
        }

        $entityManager->flush();

        return $this->redirectToRoute('editor.show', ['id' => $id]);
    }
}
