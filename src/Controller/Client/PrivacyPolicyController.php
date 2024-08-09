<?php

namespace App\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrivacyPolicyController extends AbstractController
{
    /**
     * @Route("/privacy-policy", name="privacy_policy")
     */
    public function index(): Response
    {
        return $this->render('client/privacy_policy/index.html.twig');
    }
}