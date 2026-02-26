<?php


namespace App\Controller\Admin;

use App\Entity\Bookmark;
use App\Entity\Comment;
use App\Entity\Draft;
use App\Entity\Ensemble;
use App\Entity\Event;
use App\Entity\Image;
use App\Entity\ImagePicture;
use App\Entity\ImageSimple;
use App\Entity\Like;
use App\Entity\MatrixItem;
use App\Entity\MatrixMap;
use App\Entity\Simple;
use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class DeleteController extends AbstractController
{
    private $em;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $em, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->em = $em;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * @Route("/cpanel/admin/delete", name="cpanel_admin_delete", methods={"POST"})
     */
    public function delete(Request $request): Response
    {
        // Проверка CSRF токена
        $csrfToken = new CsrfToken('delete_user', $request->request->get('_csrf_token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            throw $this->createAccessDeniedException('CSRF token invalid.');
        }

        // Получаем id пользователя
        $id = $request->request->get('userId');

        // Находим пользователя по id
        $user = $this->em->getRepository(User::class)->find($id);
        if (!$user) {
            // Обработка ошибки, если пользователь не найден
            throw $this->createNotFoundException('Пользователь с id ' . $id . ' не найден.');
        }
        // Получить название аватарки пользователя
        $image = $user->getImage();
        // Удалить картинку. Если она default.jpg или default-user.jpg, то не удалять
        if ($image != 'default.jpg' && $image != 'default-user.jpg') {
            unlink($this->getParameter('profile_image_upload_directory') . '/' . $image);
        }

        // В Draft найти все записи с user_id = id
        $drafts = $this->em->getRepository(Draft::class)->findBy(['user' => $user]);
        if ($drafts) {
            // Удалить все записи из Draft
            foreach ($drafts as $draft) {
                $this->em->remove($draft);
            }
        }

        // Удаляем Simple
        $simples = $this->em->getRepository(Simple::class)->findBy(['user' => $user]);
        // Найти записи в Image связанные с Simple
        foreach ($simples as $simple) {
            $images = $this->em->getRepository(ImageSimple::class)->findBy(['event' => $simple]);
            if ($images) {
                // Удалить все записи из Image
                foreach ($images as $image) {
                    $this->em->remove($image);
                }
            }
        }
        if ($simples) {
            // Удалить все записи из Simple
            foreach ($simples as $simple) {
                $this->em->remove($simple);
            }
        }

        // Удаляем MatrixMap
        $matrixMaps = $this->em->getRepository(MatrixMap::class)->findBy(['user' => $user]);
        // Найти записи в MatrixItem связанные с MatrixMap и удалить их
        foreach ($matrixMaps as $matrixMap) {
            $matrixItems = $this->em->getRepository(MatrixItem::class)->findBy(['event' => $matrixMap]);
            if ($matrixItems) {
                // Удалить все записи из MatrixItem
                foreach ($matrixItems as $matrixItem) {
                    $this->em->remove($matrixItem);
                }
            }
        }
        if ($matrixMaps) {
            // Удалить все записи из MatrixMap
            foreach ($matrixMaps as $matrixMap) {
                $this->em->remove($matrixMap);
            }
        }

        // Удаляем Picture
        $pictures = $this->em->getRepository(Simple::class)->findBy(['user' => $user]);
        // Найти записи в Image связанные с Picture
        foreach ($pictures as $picture) {
            $images = $this->em->getRepository(ImagePicture::class)->findBy(['event' => $picture]);
            if ($images) {
                // Удалить все записи из Image
                foreach ($images as $image) {
                    $this->em->remove($image);
                }
            }
        }
        if ($pictures) {
            // Удалить все записи из Picture
            foreach ($pictures as $picture) {
                $this->em->remove($picture);
            }
        }

        // В Event найти все записи с user_id = id
        $events = $this->em->getRepository(Event::class)->findBy(['user' => $user]);
        // Найти записи в Image связанные с Event
        foreach ($events as $event) {
            $images = $this->em->getRepository(Image::class)->findBy(['event' => $event]);
            if ($images) {
                // Удалить все записи из Image
                foreach ($images as $image) {
                    $this->em->remove($image);
                }
            }
        }
        if ($events) {
            // Удалить все записи из Event
            foreach ($events as $event) {
                $this->em->remove($event);
            }
        }
        // В Like найти все записи с user_id = id
        $likes = $this->em->getRepository(Like::class)->findBy(['user' => $user]);
        if ($likes) {
            // Удалить все записи из Like
            foreach ($likes as $like) {
                $this->em->remove($like);
            }
        }
        // В Bookmark найти все записи с user_id = id
        $bookmarks = $this->em->getRepository(Bookmark::class)->findBy(['user' => $user]);
        if ($bookmarks) {
            // Удалить все записи из Bookmark
            foreach ($bookmarks as $bookmark) {
                $this->em->remove($bookmark);
            }
        }
        // В Subscription найти все записи с user_id = id
        $subscriptionsFollower = $this->em->getRepository(Subscription::class)->findBy(['follower' => $user]);
        if ($subscriptionsFollower) {
            // Удалить все записи из Subscription
            foreach ($subscriptionsFollower as $subscription) {
                $this->em->remove($subscription);
            }
        }
        $subscriptionsFollowed = $this->em->getRepository(Subscription::class)->findBy(['followed' => $user]);
        if ($subscriptionsFollowed) {
            // Удалить все записи из Subscription
            foreach ($subscriptionsFollowed as $subscription) {
                $this->em->remove($subscription);
            }
        }
        // В Comment найти все записи с user_id = id
        $comments = $this->em->getRepository(Comment::class)->findBy(['user' => $user]);
        if ($comments) {
            // Удалить все записи из Comment
            foreach ($comments as $comment) {
                $this->em->remove($comment);
            }
        }
        // В Ensemble найти все записи с user_id = id
        $ensembles = $this->em->getRepository(Ensemble::class)->findBy(['author' => $user]);
        if ($ensembles) {
            // Удалить все записи из Ensemble
            foreach ($ensembles as $ensemble) {
                $this->em->remove($ensemble);
            }
        }

        // Удаляем пользователя
        $this->em->remove($user);

        // Сохраняем изменения
        $this->em->flush();

        // Перенаправляем на главную страницу админки
        return $this->redirectToRoute('cpanel_admin');
    }
}
