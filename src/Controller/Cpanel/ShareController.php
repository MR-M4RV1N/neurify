<?php

namespace App\Controller\Cpanel;

use App\Entity\Event;
use App\Entity\EventShared;
use App\Repository\EventRepository;
use App\Repository\EventSharedRepository;
use App\Service\FacebookPostService;
use App\Service\WebhookPublisher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ShareController extends AbstractController
{
    /** @var EventRepository */
    private $events;

    /** @var EventSharedRepository */
    private $sharedRepo;

    /** @var EntityManagerInterface */
    private $em;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var WebhookPublisher */
    private $webhookPublisher;

    /** @var CsrfTokenManagerInterface */
    private $csrf;

    /** @var Security */
    private $security;

    public function __construct(
        EventRepository $events,
        EventSharedRepository $sharedRepo,
        EntityManagerInterface $em,
        UrlGeneratorInterface $urlGenerator,
        WebhookPublisher $webhookPublisher,
        CsrfTokenManagerInterface $csrf,
        Security $security
    ) {
        $this->events = $events;
        $this->sharedRepo = $sharedRepo;
        $this->em = $em;
        $this->urlGenerator = $urlGenerator;
        $this->webhookPublisher = $webhookPublisher;
        $this->csrf = $csrf;
        $this->security = $security;
    }

    /**
     * Генерация и сохранение черновика поста
     * @Route("/cpanel/share/generate/{id}", requirements={"id"="\d+"}, name="app_share_generate", methods={"POST"})
     */
    public function generate(Event $event, FacebookPostService $fb, Request $request): Response
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания поста.');
        }

        $submittedToken = (string) $request->request->get('_token', '');
        if (!$this->csrf->isTokenValid(new CsrfToken('app_share_generate', $submittedToken))) {
            throw $this->createAccessDeniedException('Неверный CSRF токен.');
        }

        // Проверяем, существует ли запись для события
        $existing = $this->sharedRepo->findOneBy(['event' => $event]);
        if ($existing) {
            $this->addFlash('info', 'Пост для этого события уже сгенерирован.');
            return $this->redirectToRoute('app_share_show', ['id' => $event->getId()], 303);
        }

        // Дополнительные рекомендации для ИИ
        $aiHint = (string) $request->request->get('ai_hint', '');
        // Генерируем текст поста с учётом рекомендаций
        $text = $fb->generatePost($event, $aiHint);

        // Сохраняем как черновик
        $shared = new EventShared();
        $shared->setEvent($event)
            ->setUser($user)
            ->setPost($text)
            ->setPublished(false);

        $this->em->persist($shared);
        $this->em->flush();

        $this->addFlash('success', 'Пост сгенерирован и сохранён.');
        return $this->redirectToRoute('app_share_show', ['id' => $event->getId()], 303);
    }

    /**
     * Просмотр последнего сгенерированного поста
     * @Route("/cpanel/share/{id}", name="app_share_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        $last = $this->sharedRepo->findOneBy(['event' => $event], ['id' => 'DESC']);

        return $this->render('cpanel/share/share.html.twig', [
            'event'    => $event,
            'shared'   => $last,
        ]);
    }

    /**
     * Отправка поста во внешний канал (webhook) и пометка "published"
     * @Route("/cpanel/share/send", name="app_share_send", methods={"POST"})
     */
    public function send(Request $request): Response
    {
        $submittedToken = (string) $request->request->get('_token', '');
        if (!$this->csrf->isTokenValid(new CsrfToken('app_share_send', $submittedToken))) {
            throw $this->createAccessDeniedException('Неверный CSRF токен.');
        }

        $eventId = (int) $request->request->get('event_id');
        /** @var Event|null $event */
        $event = $this->events->find($eventId);
        if (!$event) {
            throw $this->createNotFoundException('Событие не найдено.');
        }

        // Берём последний черновик по событию (при необходимости фильтруй по пользователю)
        /** @var EventShared|null $last */
        $last = $this->sharedRepo->findOneBy(['event' => $event], ['id' => 'DESC']);
        if (!$last) {
            throw $this->createNotFoundException('Нет сгенерированных постов для этого события.');
        }

        $url = $this->urlGenerator->generate(
            'app_event_show',
            ['id' => $event->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $images = $event->getImages();
        $imageUrl = null;
        if (!$images->isEmpty()) {
            /** @var \App\Entity\Image $firstImage */
            $firstImage = $images->first();
            $imageUrl = 'https://neurify.life/cpanel/images/events/collections/' . $firstImage->getUrl();
        }
        else {
            $imageUrl = 'https://neurify.life/cpanel/images/maps/default.jpg';
        }

        $user = $this->security->getUser();
        $this->webhookPublisher->sendForUser($user, [
            'title'       => $event->getTitle(),
            'description' => $last->getPost(),
            'url'         => $url,
            'image'       => $imageUrl,
        ]);

        // Помечаем как опубликованный
        $last->setPublished(true);
        $this->em->flush();

        $this->addFlash('success', 'Пост отправлен в канал.');
        return $this->redirectToRoute('app_account_statistics', [], 303);
    }

    /**
     * @Route("/cpanel/share/edit/{id}", name="app_share_edit", methods={"POST"})
     */
    public function edit(
        EventShared $shared,
        Request $request
    ): Response {
        $user = $this->security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы.');
        }

        // только автор записи может редактировать
        if ($shared->getUser()->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException('У вас нет прав редактировать этот пост.');
        }

        $submittedToken = (string) $request->request->get('_token', '');
        if (!$this->csrf->isTokenValid(new CsrfToken('app_share_edit_' . $shared->getId(), $submittedToken))) {
            throw $this->createAccessDeniedException('Неверный CSRF токен.');
        }

        $newPost = (string) $request->request->get('post', '');

        // Обновляем текст поста
        $shared->setPost($newPost);

        // При редактировании логично сбросить статус публикации
        $shared->setPublished(false);

        $this->em->flush();

        $this->addFlash('success', 'Пост обновлён.');
        return $this->redirectToRoute('app_share_show', ['id' => $shared->getEvent()->getId()], 303);
    }

    /**
     * Удаление черновика поста
     * @Route("/cpanel/share/delete", name="app_share_delete", methods={"POST"})
     */
    public function delete(Request $request): Response
    {
        $submittedToken = (string) $request->request->get('_token', '');
        if (!$this->csrf->isTokenValid(new CsrfToken('app_share_delete', $submittedToken))) {
            throw $this->createAccessDeniedException('Неверный CSRF токен.');
        }

        $eventId = (int) $request->request->get('event_id');
        $event   = $this->events->find($eventId);

        if (!$event) {
            throw $this->createNotFoundException('Событие не найдено.');
        }

        // ищем запись для этого события и текущего пользователя
        $user = $this->security->getUser();
        $shared = $this->sharedRepo->findOneBy([
            'event' => $event,
            'user'  => $user,
        ]);

        if (!$shared) {
            $this->addFlash('warning', 'Запись для удаления не найдена.');
            return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
        }

        $this->em->remove($shared);
        $this->em->flush();

        $this->addFlash('success', 'Пост удалён.');
        return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
    }
}