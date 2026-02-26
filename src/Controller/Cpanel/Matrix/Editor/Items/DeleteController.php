<?php

namespace App\Controller\Cpanel\Matrix\Editor\Items;

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
     * @Route("/cpanel/editor/item/delete/{id}", name="item.delete")
     */
    public function deleteItem(EntityManagerInterface $manager, ManagerRegistry $doctrine, $id): Response
    {
        $item = $doctrine->getRepository(MatrixItem::class)->find($id);
        if(!empty($item->getFilename())) {
            $path = $this->getParameter('items_images_directory').'/'.$item->getFilename();
            $filesystem = new Filesystem();
            $result = $filesystem->remove($path);
            if ($result === false) {
                throw new \Exception(sprintf('Error deleting "%s"', $path));
            }
        }
        $manager->remove($item);
        $manager->flush();

        return $this->redirectToRoute('editor.show', ['id' => $item->getMatrixMap()->getId()]);
    }

    /**
     * @Route("/cpanel/editor/item/image/delete/{id}", name="item.image.delete")
     */
    public function deleteItemImage(EntityManagerInterface $manager, ManagerRegistry $doctrine, $id): Response
    {
        $item = $doctrine->getRepository(MatrixItem::class)->find($id);
        if(!empty($item->getFilename())) {
            $path = $this->getParameter('items_images_directory').'/'.$item->getFilename();
            $filesystem = new Filesystem();
            $result = $filesystem->remove($path);
            if ($result === false) {
                throw new \Exception(sprintf('Error deleting "%s"', $path));
            }
        }
        $item->setFilename(null);
        $manager->flush();

        return $this->redirectToRoute('editor.show', ['id' => $item->getMatrixMap()->getId()]);
    }
}
