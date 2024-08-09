<?php

namespace App\Controller;

use App\Entity\Draft;
use App\Entity\Example;
use App\Entity\Progress;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\EmailVerifierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationDetailsController extends AbstractController
{
    /**
     * @Route("/register/details", name="app_register_details")
     */
    public function registerDetails(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        // Get the user from the database
        $user = $this->getUser();
        // Create a new form
        $form = $this->createForm(RegistrationDetailsFormType::class, $user);
        // Handle the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the image upload
            $image = $form->get('image')->getData();
            
            // Save the user to the database
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/details.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
