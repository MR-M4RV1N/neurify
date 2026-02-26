<?php

namespace App\Controller\Cpanel\Matrix\Editor\Map;

use App\Entity\Category;
use App\Entity\Ensemble;
use App\Entity\Item;
use App\Entity\Map;
use App\Entity\MatrixMap;
use App\Entity\User;
use App\Service\ImageUploadHandlerService;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PersistController extends AbstractController
{
    // newMap
    /**
     * @Route("cpanel/matrix/editor/map/add", name="map.add", methods={"GET"})
    */
    public function addMap(ManagerRegistry $doctrine): Response
    {
        return $this->render('cpanel/matrix/editor/map/add.html.twig', [
            'ensembles' => $doctrine->getRepository(Ensemble::class)->findBy(['author' => $this->getUser()]),
        ]);
    }

    /**
     * @Route("cpanel/matrix/editor/map/create", name="map.create", methods={"POST"})
     */
    public function createMap(
        ManagerRegistry $doctrine,
        Request $request,
        ImageUploadHandlerService $imageUploadHandler,
        SluggerInterface $slugger
    ): Response {
        $entityManager = $doctrine->getManager();

        $map = new MatrixMap();
        $map->setName($request->get('title'));
        $map->setDescription($request->get('description'));
        $map->setPublic($request->request->has('public'));
        $map->setPortfolio($request->request->has('portfolio'));
        $map->setCreatedAt(new \DateTimeImmutable());
        $map->setUpdatedAt(new \DateTimeImmutable());

        // Привязка пользователя
        $user = $this->getUser();
        if ($user) {
            $map->setUser($doctrine->getRepository(User::class)->find($user->getId()));
        }

        // Привязка Ensemble
        $ensembleId = $request->get('ensemble');
        if ($ensembleId && $ensembleId !== 'empty') {
            $ensemble = $doctrine->getRepository(Ensemble::class)->find($ensembleId);
            if ($ensemble) {
                $map->setEnsemble($ensemble);
            }
        }

        // Обработка изображения через сервис
        $file = $request->files->get('file');
        if ($file) {
            try {
                $imageUploadHandler->setUploadDirectory($this->getParameter('maps_images_directory'));
                $newFilename = $imageUploadHandler->handleUploadFile($file);
                $map->setFilename($newFilename);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Ошибка загрузки изображения: ' . $e->getMessage());
            }
        }

        $entityManager->persist($map);
        $entityManager->flush();

        return $this->redirectToRoute('app_events_list', ['ensemble' => $ensembleId ?? 0]);
    }
}
