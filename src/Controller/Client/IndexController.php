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
use App\Data\FeatureData;
use App\Data\LandingTestimonialData;
use App\Data\MentorData;
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
        return $this->redirect('/lv/');
    }

    /**
     * @Route("/{_locale<lv|ru|en>}/", name="client.index")
     */
    public function index(Request $request, ManagerRegistry $doctrine, $_locale): Response
    {
        $request->setLocale($_locale);
        $request->getSession()->set('_locale', $_locale);

        $userRepo = $doctrine->getRepository(\App\Entity\User::class);
        $totalUsers = $userRepo->count([]);
        $latestAvatarUsers = $userRepo->createQueryBuilder('u')
            ->where('u.image IS NOT NULL')
            ->andWhere('u.image != \'\'')
            ->andWhere('u.image NOT LIKE :defaultString')
            ->setParameter('defaultString', '%default%')
            ->orderBy('u.id', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();

        $testimonialUsernames = ['User', 'SocialInspirator_LV', 'BioHacker_LV'];
        $testimonialUsersRaw = $userRepo->createQueryBuilder('u')
            ->where('u.username IN (:usernames)')
            ->setParameter('usernames', $testimonialUsernames)
            ->getQuery()
            ->getResult();

        $testimonialUsersMap = [];
        foreach ($testimonialUsersRaw as $u) {
            $testimonialUsersMap[$u->getUsername()] = $u;
        }

        $landingTestimonials = LandingTestimonialData::getAll($_locale);
        foreach ($landingTestimonials as $i => &$t) {
            $username = $testimonialUsernames[$i] ?? null;
            if ($username && isset($testimonialUsersMap[$username])) {
                $user = $testimonialUsersMap[$username];
                $t['name'] = $user->getFirstname();
                if ($user->getImage()) {
                    $t['avatar'] = '/cpanel/profile/uploads/images/' . $user->getImage();
                }
            }
        }

        return $this->render('client/index.html.twig', [
            'about_the_adventure' => $doctrine->getRepository(Wording::class)->findOneBy(['name' => 'about_the_adventure', 'lang' => $_locale])->getText(),
            'about_the_project' => $doctrine->getRepository(Wording::class)->findOneBy(['name' => 'about_the_project', 'lang' => $_locale])->getText(),
            'comfort_zone' => $doctrine->getRepository(Wording::class)->findOneBy(['name' => 'comfort_zone', 'lang' => $_locale])->getText(),
            'inspiration' => $doctrine->getRepository(Wording::class)->findOneBy(['name' => 'inspiration', 'lang' => $_locale])->getText(),
            'neuroplasticity' => $doctrine->getRepository(Wording::class)->findOneBy(['name' => 'neuroplasticity', 'lang' => $_locale])->getText(),
            'global_problem' => $doctrine->getRepository(Wording::class)->findOneBy(['name' => 'global_problem', 'lang' => $_locale])->getText(),
            'lets_try' => $doctrine->getRepository(Wording::class)->findOneBy(['name' => 'lets_try', 'lang' => $_locale])->getText(),
            'benefits' => $doctrine->getRepository(WordingBenefit::class)->findBy(['lang' => $_locale]),
            'testimonials' => $doctrine->getRepository(Testimonial::class)->findBy(['lang' => $_locale]),
            'landing_testimonials' => $landingTestimonials,
            'features' => $doctrine->getRepository(WordingFeatures::class)->findBy(['lang' => $_locale]),
            'landing_features' => FeatureData::getAll($_locale),
            'total_users' => $totalUsers,
            'avatar_users' => $latestAvatarUsers,
            'mentors' => MentorData::getAll($_locale),
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
            ->text('Sender : ' . $request->request->get('fullname') . ' (' . $request->request->get('email') . ')')
            ->html('<p>Message: ' . $request->request->get('message') . '</p>');

        $mailer->send($email);

        $this->addFlash('success', 'Your message has been sent!');

        return $this->redirectToRoute('client_default');
    }
}
