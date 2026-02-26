<?php

namespace App\Controller\Cpanel\Matrix\Editor;

use App\Entity\MatrixItem;
use App\Entity\MatrixMap;
use App\Service\ArrayFromItemsService;
use App\Service\ImageUploadHandlerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MatrixApiController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    /**
     * @Route("/api/matrix/{id}", name="api_matrix_show", methods={"GET"})
     */
    public function apiShow(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $mapRepository = $doctrine->getRepository(MatrixMap::class);
        $itemRepository = $doctrine->getRepository(MatrixItem::class);

        $map = $mapRepository->find($id);

        if (!$map) {
            return $this->json(['error' => 'Matrix not found'], 404);
        }

        $items = $itemRepository->findBy(['matrix_map' => $map]);
        $structuredItems = ArrayFromItemsService::getArray($items);

        return $this->json([
            'id' => $map->getId(),
            'name' => $map->getName(),
            'description' => $map->getDescription(),
            'filename' => $map->getFilename(),
            'user' => [
                'id' => $map->getUser()->getId(),
                'firstname' => $map->getUser()->getFirstname(),
                'lastname' => $map->getUser()->getLastname(),
            ],
            'items' => $structuredItems,
        ]);
    }

    /**
     * @Route("/api/matrix/add/{id}", name="api_matrix_add_form_data", methods={"GET"})
     */
    public function apiAddFormData(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $mapRepository = $doctrine->getRepository(MatrixMap::class);
        $map = $mapRepository->find($id);

        if (!$map) {
            return $this->json(['error' => 'Matrix not found'], 404);
        }

        return $this->json([
            'id' => $map->getId(),
            'name' => $map->getName(),
        ]);
    }

    /**
     * @Route("/api/matrix/item/create/{id}", name="api_matrix_item_create", methods={"POST"})
     */
    public function apiCreateItem(
        ManagerRegistry $doctrine,
        Request $request,
        ImageUploadHandlerService $imageUploadHandler,
        int $id
    ): JsonResponse {
        $entityManager = $doctrine->getManager();
        $map = $doctrine->getRepository(MatrixMap::class)->find($id);

        if (!$map) {
            return $this->json(['error' => 'Matrix map not found'], 404);
        }

        $existingItems = $doctrine->getRepository(MatrixItem::class)->findBy(['matrix_map' => $id]);

        if (count($existingItems) >= 9) {
            return $this->json(['error' => 'Items limit is 9'], 400);
        }

        $item = new MatrixItem();
        $item->setTitle($request->request->get('title')); // Используем $request->request для обычных форм
        $item->setDescription($request->request->get('description'));
        $item->setMatrixMap($map);

        // Загрузка файла
        $file = $request->files->get('file');
        if ($file instanceof UploadedFile && !$file->getError()) {
            try {
                $imageUploadHandler->setUploadDirectory($this->getParameter('items_images_directory'));
                $newFilename = $imageUploadHandler->handleUploadFile($file);
                $item->setFilename($newFilename);
            } catch (\Exception $e) {
                return $this->json(['error' => 'Ошибка загрузки изображения: ' . $e->getMessage()], 400);
            }
        }

        $entityManager->persist($item);
        $entityManager->flush();

        return $this->json(['success' => 'Item created successfully', 'itemId' => $item->getId()], 201);
    }

    /**
     * @Route("/api/matrix/item/{id}", name="api_matrix_item_show", methods={"GET"})
     */
    public function apiGetItem(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $item = $doctrine->getRepository(MatrixItem::class)->find($id);

        if (!$item) {
            return $this->json(['error' => 'Item not found'], 404);
        }

        return $this->json([
            'id' => $item->getId(),
            'title' => $item->getTitle(),
            'description' => $item->getDescription(),
            'filename' => $item->getFilename(),
            'matrixMapId' => $item->getMatrixMap()->getId(),
        ]);
    }

    /**
     * @Route("/api/matrix/item/edit/{id}", name="api_matrix_item_edit", methods={"POST"})
     */
    public function apiEditItem(
        ManagerRegistry $doctrine,
        Request $request,
        ImageUploadHandlerService $imageUploadHandler,
        int $id
    ): JsonResponse {
        $entityManager = $doctrine->getManager();
        $item = $doctrine->getRepository(MatrixItem::class)->find($id);

        if (!$item) {
            return $this->json(['error' => 'Item not found'], 404);
        }

        $item->setTitle($request->request->get('title'));
        $item->setDescription($request->request->get('description'));

        $file = $request->files->get('file');
        if ($file instanceof UploadedFile && !$file->getError()) {
            try {
                $imageUploadHandler->setUploadDirectory($this->getParameter('items_images_directory'));
                $newFilename = $imageUploadHandler->handleUploadFile($file);
                $item->setFilename($newFilename);
            } catch (\Exception $e) {
                return $this->json(['error' => 'Ошибка загрузки изображения: ' . $e->getMessage()], 400);
            }
        }

        $entityManager->flush();

        return $this->json(['success' => 'Item updated successfully', 'itemId' => $item->getId()], 200);
    }
}
