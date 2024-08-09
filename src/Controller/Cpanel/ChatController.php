<?php

namespace App\Controller\Cpanel;

use App\Entity\Chat;
use App\Entity\Messages;
use App\Entity\Participant;
use App\Entity\User;
use App\Form\MessagesType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cpanel/chat")
 */
class ChatController extends AbstractController
{
    /**
     * @Route("/chat_list", name="cpanel_chat_list")
     */
    public function list(ManagerRegistry $doctrine): Response
    {
        $participant = $doctrine->getRepository(Participant::class)->findBy(['user' => $this->getUser()]);
        $arr = [];
        foreach($participant as $p) {
            $recitedNotMessages = $doctrine->getRepository(Messages::class)->findMessagesNotFromUser($p->getChat(), $this->getUser());

            $countRecited = $doctrine->getRepository(Messages::class)->findBy([
                'sender' => $this->getUser(),
                'chat' => $p->getChat(),
                'recited' => false,
            ]);
            $arr[] = [
                'id' => $p->getChat()->getId(),
                'title' => $p->getChat()->getTitle(),
                'updatedAt' => $p->getChat()->getUpdatedAt(),
                'recited' => count($recitedNotMessages)
            ];
        }

        // Сортируем массив по 'updatedAt' в убывающем порядке
        usort($arr, function ($a, $b) {
            return $b['updatedAt'] <=> $a['updatedAt']; // Используем оператор космического корабля для PHP 7+
        });

        return $this->render('cpanel/chat/chat_list.html.twig', [
            'myChats' => $arr
        ]);
    }

    /**
     * @Route("/check/{id}", name="cpanel_chat_check")
     */
    public function checkChat(ManagerRegistry $doctrine, User $user): Response
    {
        $entityManager = $doctrine->getManager();

        $result = false;

        // Проверяем существует ли чат между пользователями
        $first = $doctrine->getRepository(Chat::class)->findOneBy(['code' => 'C'.$this->getUser()->getId().'/'.$user->getId()]);
        $second = $doctrine->getRepository(Chat::class)->findOneBy(['code' => 'C'.$user->getId().'/'.$this->getUser()->getId()]);
        if($first) {
            $result = true;
        }
        elseif($second) {
            $result = true;
        }

        return $this->json(['result' => $result]);
    }

    /**
     * @Route("/new/{id}", name="cpanel_chat_new")
     */
    public function newChat(ManagerRegistry $doctrine, User $user): Response
    {
        $entityManager = $doctrine->getManager();

        // Проверяем существует ли чат между пользователями
        $first = $doctrine->getRepository(Chat::class)->findOneBy(['code' => 'C'.$this->getUser()->getId().'/'.$user->getId()]);
        $second = $doctrine->getRepository(Chat::class)->findOneBy(['code' => 'C'.$user->getId().'/'.$this->getUser()->getId()]);
        if($first) {
            $chat = $first;
        }
        elseif($second) {
            $chat = $second;
        }
        else {
            $chat = new Chat();
            $chat->setTitle($this->getUser()->getUsername().' / '.$user->getUsername() );
            $chat->setCode('C'.$this->getUser()->getId().'/'.$user->getId());
            $chat->setCreatedAt(new \DateTimeImmutable());
            $chat->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($chat);

            $participantS = new Participant;
            $participantS->setChat($chat);
            $participantS->setUser($this->getUser());
            $entityManager->persist($participantS);

            $participantR = new Participant;
            $participantR->setChat($chat);
            $participantR->setUser($user);
            $entityManager->persist($participantR);

            $entityManager->flush();
        }

        return $this->redirectToRoute('cpanel_chat_message_list', [
            'id' => $chat->getId()
        ], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/message_list/{id}", name="cpanel_chat_message_list")
     */
    public function messageList(ManagerRegistry $doctrine, $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $chat = $doctrine->getRepository(Chat::class)->find($id);

        // Помечаем все сообщения как прочитанные
        $userMessages = $doctrine->getRepository(Messages::class)->findMessagesNotFromUser($chat, $this->getUser());
        foreach($userMessages as $m) {
            $m->setRecited(true);
        }
        $entityManager->flush();

        // Подготавливаем сообщение
        $message = new Messages();
        $message->setRecited(false);
        $message->setEdited(false);
        $message->setDeleted(false);
        $message->setDate(new \DateTime());
        $message->setTime(new \DateTime());
        $message->setSender($this->getUser());
        $message->setChat($chat);
        $message->setCreatedAt(new \DateTimeImmutable());

        // Работаем с формой
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $chat->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('cpanel_chat_message_list', [
                'id' => $chat->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/chat/messages_list.html.twig', [
            'messages' => array_reverse($doctrine->getRepository(Messages::class)->findBy(
                ['chat' => $chat],      // Критерии фильтрации
                ['createdAt' => 'DESC'], // Сортировка по убыванию даты создания (предполагаем, что есть поле createdAt)
                10
            )),
            'form' => $form,
            'chatTitle' => $chat->getTitle()
        ]);
    }
}
