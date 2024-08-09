<?php

namespace App\Controller\Client;

use App\Entity\Item;
use App\Entity\Mail;
use App\Entity\Map;
use App\Entity\Testimonial;
use App\Entity\Wording;
use App\Entity\WordingBenefit;
use App\Entity\WordingFeatures;
use App\Repository\MapRepository;
use App\Repository\WordingBenefitRepository;
use App\Repository\WordingRepository;
use App\Service\ArrayFromItemsService;
use App\Service\PaginatorConfig;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="client_default")
     */
    public function default(): Response
    {
        return $this->redirect('/ru/');
    }

    /**
     * @Route("/{_locale<lv|ru|en>}/", name="client.index")
     */
    public function index(Request $request, ManagerRegistry $doctrine, $_locale): Response
    {
        $request->setLocale($_locale);
        $request->getSession()->set('_locale', $_locale);

        return $this->render('client/index.html.twig', [
            'about_the_project' => $doctrine->getRepository(Wording::class)->findOneBy(['name' => 'about_the_project', 'lang' => $_locale])->getText(),
            'neuroplasticity' => $doctrine->getRepository(Wording::class)->findOneBy(['name' => 'neuroplasticity', 'lang' => $_locale])->getText(),
            'global_problem' => $doctrine->getRepository(Wording::class)->findOneBy(['name' => 'global_problem', 'lang' => $_locale])->getText(),
            'lets_try' => $doctrine->getRepository(Wording::class)->findOneBy(['name' => 'lets_try', 'lang' => $_locale])->getText(),
            'benefits' => $doctrine->getRepository(WordingBenefit::class)->findBy(['lang' => $_locale]),
            'testimonials' => $doctrine->getRepository(Testimonial::class)->findBy(['lang' => $_locale]),
            'features' => $doctrine->getRepository(WordingFeatures::class)->findBy(['lang' => $_locale]),
        ]);
    }

    /**
     * @Route("/client/send", name="contact_send", methods={"POST"})
     */
    public function send(Request $request, MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from($request->request->get('email'))
            ->to('support@neurify.life')
            ->subject('Mail from Neurify')
            ->text('Sender : '.$request->request->get('fullname').' ('.$request->request->get('email').')')
            ->html('<p>Message: '.$request->request->get('message').'</p>');

        $mailer->send($email);

        $this->addFlash('success', 'Your message has been sent!');

        return $this->redirectToRoute('client_default');
    }
}
