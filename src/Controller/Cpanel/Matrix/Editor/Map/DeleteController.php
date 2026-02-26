<?php

namespace App\Controller\Cpanel\Matrix\Editor\Map;

use App\Entity\Item;
use App\Entity\Map;
use App\Entity\MatrixItem;
use App\Entity\MatrixMap;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends AbstractController
{
    /**
     * @Route("/cpanel/editor/map/delete/{id}", name="map.delete")
     */
    public function deleteMap(EntityManagerInterface $manager, ManagerRegistry $doctrine, $id): Response
    {
        $map = $doctrine->getRepository(MatrixMap::class)->find($id);
        if(!empty($map->getFilename())) {
            $path = $this->getParameter('maps_images_directory').'/'.$map->getFilename();
            $filesystem = new Filesystem();
            $result = $filesystem->remove($path);
            if ($result === false) {
                throw new \Exception(sprintf('Error deleting "%s"', $path));
            }
        }
        $item = $doctrine->getRepository(MatrixItem::class)->findBy(['matrix_map' => $map]);
        foreach($item as $i) {
            if(!empty($i->getFilename())) {
                $items_images_path = $this->getParameter('items_images_directory').'/'.$i->getFilename();
                $filesystem = new Filesystem();
                $result = $filesystem->remove($items_images_path);
                if ($result === false) {
                    throw new \Exception(sprintf('Error deleting "%s"', $items_images_path));
                }
            }
            $manager->remove($i);
        }
        $manager->remove($map);
        $manager->flush();

        return $this->redirectToRoute('app_events_list', ['category' => 0]);
    }
}
