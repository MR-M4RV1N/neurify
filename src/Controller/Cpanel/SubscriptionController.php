<?php
// src/Controller/SubscriptionController.php

namespace App\Controller\Cpanel;

use App\Entity\User;
use App\Entity\Subscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SubscriptionController extends AbstractController
{
    /**
     * @Route("/subscribe/{id}", name="subscribe_user")
     */
    public function subscribe(User $userToFollow, EntityManagerInterface $em): Response
    {
        $currentUser = $this->getUser();

        if (!$currentUser) {
            throw new AccessDeniedException('You need to be logged in to subscribe.');
        }

        if ($currentUser === $userToFollow) {
            $this->addFlash('error', 'You cannot follow yourself.');
            return $this->redirectToRoute('app_users_user', ['id' => $userToFollow->getId()]);
        }

        $subscriptionRepository = $em->getRepository(Subscription::class);
        $existingSubscription = $subscriptionRepository->findOneBy([
            'follower' => $currentUser,
            'followed' => $userToFollow
        ]);

        if (!$existingSubscription) {
            $subscription = new Subscription($currentUser, $userToFollow);
            $em->persist($subscription);
            $em->flush();

            $this->addFlash('success', 'You are now following ' . $userToFollow->getUsername());
        }

        return $this->redirectToRoute('app_users_user', ['id' => $userToFollow->getId()]);
    }

    /**
     * @Route("/unsubscribe/{id}", name="unsubscribe_user")
     */
    public function unsubscribe(User $userToUnfollow, EntityManagerInterface $em): Response
    {
        $currentUser = $this->getUser();

        if (!$currentUser) {
            throw new AccessDeniedException('You need to be logged in to unsubscribe.');
        }

        $subscriptionRepository = $em->getRepository(Subscription::class);
        $subscription = $subscriptionRepository->findOneBy([
            'follower' => $currentUser,
            'followed' => $userToUnfollow
        ]);

        if ($subscription) {
            $em->remove($subscription);
            $em->flush();

            $this->addFlash('success', 'You have unfollowed ' . $userToUnfollow->getUsername());
        }

        return $this->redirectToRoute('app_users_user', ['id' => $userToUnfollow->getId()]);
    }
}
