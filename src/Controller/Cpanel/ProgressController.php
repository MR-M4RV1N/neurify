<?php

namespace App\Controller\Cpanel;

use App\Entity\Progress;
use App\Entity\Event;
use App\Entity\Level;
use App\Form\ProgressType;
use App\Repository\ProgressRepository;
use App\Repository\EventRepository;
use App\Service\ArrayFromItemsService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/cpanel/progress")
 */
class ProgressController extends AbstractController
{
    /**
     * @Route("/", name="app_progress_index", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $level = $doctrine->getRepository(Level::class);
        $progress = $this->getUser()->getProgress();
        // Если $progress пуст, то устанавливаем первый уровень
        if (!$progress) {
            $progress = 1;
        }
        $myLevel = $progress % 10 == 0 ? ceil($progress / 10) + 1 : ceil($progress / 10);

        return $this->render('cpanel/progress/index.html.twig', [
            'level' => $level->findOneBy(['lang' => $this->getUser()->getLang(), 'number' => $myLevel]),
            'levels' => $level->findBy(['lang' => $this->getUser()->getLang()])
        ]);
    }

    /**
     * @Route("/new", name="app_progress_new", methods={"GET", "POST"})
     */
    public function new(Request $request, Security $security, ProgressRepository $progressRepository): Response
    {
        $progress = new Progress();

        // Получаем текущего пользователя
        $user = $security->getUser();
        // Убедитесь, что пользователь авторизован
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания категории.');
        }
        // Устанавливаем пользователя для категории
        $progress->setUser($user);
        $progress->setActive(false);

        $form = $this->createForm(ProgressType::class, $progress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $progressRepository->add($progress, true);

            return $this->redirectToRoute('app_progress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/progress/new.html.twig', [
            'progress' => $progress,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"}, name="app_progress_show", methods={"GET"})
     */
    public function show(Progress $progress): Response
    {
        return $this->render('cpanel/progress/show.html.twig', [
            'progress' => $progress,
        ]);
    }

    /**
     * @Route("/by-level/{id}", requirements={"id"="\d+"}, name="app_progress_show_by_level", methods={"GET"})
     */
    public function showByLevel($id, EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findBy(
            ['user' => $this->getUser()], // Условия выборки
            ['id' => 'ASC'],              // Условия сортировки (необязательно)
            10,                      // Максимальное количество результатов
            ($id - 1) * 10          // Смещение
        );

        return $this->render('cpanel/event/other/index_by_level.html.twig', [
            'events' => $events,
            'level' => $id
        ]);
    }

    /**
     * @Route("/{id}/edit", requirements={"id"="\d+"}, name="app_progress_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Progress $progress, ProgressRepository $progressRepository): Response
    {
        $form = $this->createForm(ProgressType::class, $progress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $progressRepository->add($progress, true);

            return $this->redirectToRoute('app_progress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/progress/edit.html.twig', [
            'progress' => $progress,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"}, name="app_progress_delete", methods={"POST"})
     */
    public function delete(ManagerRegistry $doctrine, Request $request, Progress $progress, ProgressRepository $progressRepository): Response
    {
        if($progress->isActive() == true) {
            $entityManager = $doctrine->getManager();
            // Находим новую категорию для активации
            $newActiveProgress = $entityManager->getRepository(Progress::class)->findOneBy(['user' => $this->getUser()]);
            // Устанавливаем новую категорию как активную
            if ($newActiveProgress) {
                $newActiveProgress->setActive(true);
            }
            $entityManager->flush();
        }

        if ($this->isCsrfTokenValid('delete'.$progress->getId(), $request->request->get('_token'))) {
            $progressRepository->remove($progress, true);
        }

        return $this->redirectToRoute('app_progress_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/active/{id}", requirements={"id"="\d+"}, name="app_progress_active")
     */
    public function active(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();

        // Находим текущую активную категорию
        $oldActiveProgress = $entityManager->getRepository(Progress::class)->findOneBy([
            'user' => $this->getUser(),
            'active' => true
        ]);
        // Если нашли старую активную категорию, убираем активность
        if ($oldActiveProgress) {
            $oldActiveProgress->setActive(false);
        }

        // Находим новую категорию для активации
        $newActiveProgress = $entityManager->getRepository(Progress::class)->find($id);
        // Устанавливаем новую категорию как активную
        if ($newActiveProgress) {
            $newActiveProgress->setActive(true);
        }

        // Выполняем один flush для применения всех изменений в базе данных
        $entityManager->flush();

        return $this->redirectToRoute('app_progress_index');
    }

    /**
     * @Route("/continue", name="app_progress_continue", methods={"POST"})
     */
    public function continue(ManagerRegistry $doctrine, Security $security, ProgressRepository $progressRepository): Response
    {
        // Получаем текущего пользователя
        $user = $security->getUser();
        // Убедитесь, что пользователь авторизован
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания категории.');
        }

        $entityManager = $doctrine->getManager();
        // Находим текущую активную категорию
        $oldActiveProgress = $entityManager->getRepository(Progress::class)->findOneBy([
            'user' => $this->getUser(),
            'active' => true
        ]);
        // Если нашли старую активную категорию, убираем активность
        if ($oldActiveProgress) {
            $oldActiveProgress->setActive(false);
        }

        $progress = new Progress();
        $progress->setUser($user);
        $progress->setTitle('Kolekcija '.date('Y-m-d'));
        $progress->setActive(true);
        $progressRepository->add($progress, true);

        return $this->redirectToRoute('app_events_list', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/by/{id}", name="app_events_by_id", methods={"GET"})
     */
    public function showById(EventRepository $eventRepository, ProgressRepository $progressRepository, $id): Response
    {
        $progress = $progressRepository->findOneBy(['user' => $this->getUser(), 'id' => $id]);
        $events = $eventRepository->findBy(['user' => $this->getUser(), 'progress' => $progress]);

        return $this->render('cpanel/event/index.html.twig', [
            'events' => $events,
            'progress' => $progress,
        ]);
    }

    /**
     * @Route("/result/by/{id}", name="app_events_result_by_id")
     */
    public function resultWithID(ManagerRegistry $doctrine, $id): Response
    {
        $progress = $doctrine->getRepository(Progress::class)->findOneBy(['user' => $this->getUser(), 'id' => $id]);

        $arr = ArrayFromItemsService::getArray($doctrine->getRepository(Event::class)->findBy(['user' =>  $this->getUser(), 'progress' => $progress]));
        return $this->render('cpanel/event/other/result.html.twig', [
            'items' => $arr,
            'progress' => $progress
        ]);
    }
}
