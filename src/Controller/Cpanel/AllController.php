<?php

namespace App\Controller\Cpanel;

use App\Consulting\Model\Disc\DiscPromptProvider;
use App\Consulting\Model\Mbti\MbtiPromptProvider;
use App\Consulting\Model\Thinker\ThinkerPromptProvider;
use App\Consulting\Model\Wealth\WealthPromptProvider;
use App\Consulting\Model\PublicService\PublicServicePromptProvider;
use App\Entity\AiCareerConsultingResult;
use App\Entity\AiConsultingResult;
use App\Entity\Bookmark;
use App\Entity\BookmarkRegister;
use App\Entity\ConsultingInsight;
use App\Entity\Ensemble;
use App\Entity\EnsembleComplete;
use App\Entity\Image;
use App\Entity\Progress;
use App\Entity\Event;
use App\Entity\Level;
use App\Entity\Swot;
use App\Entity\User;
use App\Entity\WeekChallenge;
use App\Repository\EventRepeatedRepository;
use App\Repository\EventRepository;
use App\Repository\MatrixMapRepository;
use App\Repository\PictureRepository;
use App\Repository\SimpleRepository;
use App\Service\EnsembleProgressService;
use App\Service\EventService;
use App\Service\StepwiseService;
use App\Service\UserWeekChallengeService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Pagerfanta\Adapter\ArrayAdapter;

class AllController extends AbstractController
{
    /**
     * @Route("/cpanel/all", name="all")
     */
    public function index(
        Request $request,
        ManagerRegistry $doctrine,
        MbtiPromptProvider $mbtiPromptProvider,
        DiscPromptProvider $discPromptProvider,
        ThinkerPromptProvider $thinkerPromptProvider,
        WealthPromptProvider $wealthPromptProvider,
        PublicServicePromptProvider $publicServicePromptProvider
    ): Response {
        $user = $this->getUser();
        $lang = $user->getLang();
        $mbtiStyles = ['Se', 'Si', 'Ne', 'Ni', 'Te', 'Ti', 'Fe', 'Fi'];
        $mbtiStyleDescriptions = [];
        $mbtiStyleLabels = [];
        foreach ($mbtiStyles as $style) {
            $mbtiStyleDescriptions[$style] = $mbtiPromptProvider->getStyleDescription($style, $lang);
            $mbtiStyleLabels[$style] = $mbtiPromptProvider->getStyleShortLabel($style, $lang);
        }

        $discStyles = ['D', 'I', 'S', 'C'];
        $discStyleDescriptions = [];
        $discStyleLabels = [];
        foreach ($discStyles as $style) {
            $discStyleDescriptions[$style] = $discPromptProvider->getStyleDescription($style, $lang);
            $discStyleLabels[$style] = $discPromptProvider->getStyleShortLabel($style, $lang);
        }

        $thinkerStyles = ['berne', 'taleb', 'jung'];
        $thinkerStyleDescriptions = [];
        $thinkerStyleLabels = [];
        foreach ($thinkerStyles as $style) {
            $thinkerStyleDescriptions[$style] = $thinkerPromptProvider->getStyleDescription($style, $lang);
            $thinkerStyleLabels[$style] = $thinkerPromptProvider->getStyleShortLabel($style, $lang);
        }

        $wealthStyles = ['hill', 'clason'];
        $wealthStyleDescriptions = [];
        $wealthStyleLabels = [];
        foreach ($wealthStyles as $style) {
            $wealthStyleDescriptions[$style] = $wealthPromptProvider->getStyleDescription($style, $lang);
            $wealthStyleLabels[$style] = $wealthPromptProvider->getStyleShortLabel($style, $lang);
        }

        $publicServiceStyles = ['drucker'];
        $publicServiceStyleDescriptions = [];
        $publicServiceStyleLabels = [];
        foreach ($publicServiceStyles as $style) {
            $publicServiceStyleDescriptions[$style] = $publicServicePromptProvider->getStyleDescription($style, $lang);
            $publicServiceStyleLabels[$style] = $publicServicePromptProvider->getStyleShortLabel($style, $lang);
        }

        return $this->render('cpanel/all/index.html.twig', [
            'lastConsulting' => $this->getDoctrine()
                ->getRepository(ConsultingInsight::class)
                ->findLastRecordsByUser($this->getUser()),

            'checkSwot' => $this->getDoctrine()
                ->getRepository(Swot::class)
                ->count(['user' => $this->getUser()]) > 0,

            'checkProfileDescription' => !empty($this->getUser()->getDescription()),

            'checkLastMonthEvents' => $this->getDoctrine()
                ->getRepository(Event::class)
                ->countLastMonthEventsByUser($this->getUser()) > 0,
            'mbtiStyleDescriptions' => $mbtiStyleDescriptions,
            'mbtiStyleLabels' => $mbtiStyleLabels,
            'discStyleDescriptions' => $discStyleDescriptions,
            'discStyleLabels' => $discStyleLabels,
            'thinkerStyleDescriptions' => $thinkerStyleDescriptions,
            'thinkerStyleLabels' => $thinkerStyleLabels,
            'wealthStyleDescriptions' => $wealthStyleDescriptions,
            'wealthStyleLabels' => $wealthStyleLabels,
            'publicServiceStyleDescriptions' => $publicServiceStyleDescriptions,
            'publicServiceStyleLabels' => $publicServiceStyleLabels,
        ]);
    }

    /**
     * @Route("/cpanel/settings", name="all_settings")
     */
    public function settings(
        ManagerRegistry $doctrine,
        UserWeekChallengeService $service,
        StepwiseService $stepwise,
        EnsembleProgressService $progress
    ): Response {
        $ensemble = $doctrine->getRepository(Ensemble::class)->findOneBy(['author' => $this->getUser(), 'aiGenerated' => true, 'draft' => false]);
        $data = $stepwise->buildForEnsemble($ensemble, $this->getUser());

        return $this->render(
            'cpanel/all/settings.html.twig',
            array_merge($data, [
                'checkSwot' => $this->getDoctrine()
                    ->getRepository(Swot::class)
                    ->count(['user' => $this->getUser()]) > 0,

                'checkProfileDescription' => !empty($this->getUser()->getDescription()),

                'checkLastMonthEvents' => $this->getDoctrine()
                    ->getRepository(Event::class)
                    ->countLastMonthEventsByUser($this->getUser()) > 0,
            ])
        );
    }
}
