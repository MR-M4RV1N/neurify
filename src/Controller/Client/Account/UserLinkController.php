<?php

namespace App\Controller\Client\Account;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserLinkController extends AbstractController
{
    /**
     * @Route("/u/{user}", name="app_u_index", methods={"GET"})
     */
    public function userProfileIndex(UserRepository $userRepository, $user)
    {
        if($this->getUser()->getType() > 2) {
            return $this->redirectToRoute('users_stepwise_ensembles', ['id' => $userRepository->findOneBy(['username' => $user])->getId()]);
        }
        else {
            return $this->redirectToRoute('app_users_user', ['id' => $userRepository->findOneBy(['username' => $user])->getId()]);
        }
//        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
//            return $this->redirectToRoute('app_users_user', ['id' => $userRepository->findOneBy(['username' => $user])->getId()]);
//        }
//        else{
//            return $this->redirectToRoute('client_users_statistics', ['id' => $userRepository->findOneBy(['username' => $user])->getId() ]);
//        }
    }

    /**
     * @Route("/s/{user}", name="app_s_index", methods={"GET"})
     */
    public function userResumeIndex(UserRepository $userRepository, $user)
    {
//        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
        return $this->redirectToRoute('app_users_user', ['id' => $userRepository->findOneBy(['username' => $user])->getId()]);
//        } else {
//            return $this->redirectToRoute('client_users_statistics');
//        }
    }
}