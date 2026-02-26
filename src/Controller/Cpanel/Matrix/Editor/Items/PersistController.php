<?php

namespace App\Controller\Cpanel\Matrix\Editor\Items;

use App\Entity\MatrixItem;
use App\Entity\MatrixMap;
use App\Service\ImageUploadHandlerService;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PersistController extends AbstractController
{
    /**
     * @Route("cpanel/editor/item/create/{id}", name="item.create", methods={"POST"})
     */
    public function createItem(
        ManagerRegistry $doctrine,
        Request $request,
        ImageUploadHandlerService $imageUploadHandler,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $map = $doctrine->getRepository(MatrixMap::class)->find($id);

        if (!$map) {
            throw $this->createNotFoundException('Matrix map not found');
        }

        $existingItems = $doctrine->getRepository(MatrixItem::class)->findBy(['matrix_map' => $id]);

        if (count($existingItems) >= 9) {
            return new Response('<html><body>Error: items limit is 9</body></html>', 400);
        }

        $item = new MatrixItem();
        $item->setTitle($request->get('title'));
        $item->setDescription($request->get('description'));
        $item->setMatrixMap($map);

        // Загрузка файла
        $file = $request->files->get('file');
        if ($file) {
            try {
                $imageUploadHandler->setUploadDirectory($this->getParameter('items_images_directory'));
                $newFilename = $imageUploadHandler->handleUploadFile($file);
                $item->setFilename($newFilename);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Ошибка загрузки изображения: ' . $e->getMessage());
            }
        }

        $entityManager->persist($item);
        $entityManager->flush();

        return $this->redirectToRoute('editor.show', ['id' => $id]);
    }
}
