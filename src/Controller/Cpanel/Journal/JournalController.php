<?php

namespace App\Controller\Cpanel\Journal;

use App\Entity\JournalEntry;
use App\Repository\JournalEntryRepository;
use App\Service\Journal\JournalCalendarService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class JournalController extends AbstractController
{
    /**
     * @Route("/cpanel/journal", name="cpanel_journal_index")
     */
    public function index(
        JournalCalendarService $calendarService,
        Security $security,
        Request $request
    ): Response {
        $user = $security->getUser();

        $calendar = $calendarService->buildCalendar(
            $user,
            $request->query->get('month')
        );

        return $this->render('cpanel/journal/index.html.twig', $calendar);
    }

    /**
     * @Route("/cpanel/journal/{date}", name="journal_show", requirements={"date"="\d{4}-\d{2}-\d{2}"})
     */
    public function show(
        string $date,
        JournalEntryRepository $repository,
        Security $security
    ): Response {
        $user = $security->getUser();
        $day = new \DateTimeImmutable($date);

        $entry = $repository->findOneBy([
            'user' => $user,
            'date' => $day,
        ]);

        return $this->render('cpanel/journal/show.html.twig', [
            'date'  => $day,
            'entry' => $entry,
        ]);
    }

    /**
     * @Route("/cpanel/journal/{date}/edit", name="journal_edit", requirements={"date"="\d{4}-\d{2}-\d{2}"})
     */
    public function edit(
        string $date,
        JournalEntryRepository $repository,
        Security $security
    ): Response {
        $user = $security->getUser();
        $day = new \DateTimeImmutable($date);

        $entry = $repository->findOneBy([
            'user' => $user,
            'date' => $day,
        ]);

        if (!$entry) {
            $entry = new JournalEntry();
            $entry->setUser($user);
            $entry->setDate($day);
            $entry->setCreatedAt(new \DateTimeImmutable());
        }

        return $this->render('cpanel/journal/edit.html.twig', [
            'entry' => $entry,
            'date'  => $day,
        ]);
    }

    /**
     * @Route("/cpanel/journal/{date}/update", name="journal_update", methods={"POST"}, requirements={"date"="\d{4}-\d{2}-\d{2}"})
     */
    public function update(
        string $date,
        Request $request,
        JournalEntryRepository $repository,
        EntityManagerInterface $em,
        Security $security
    ): Response {
        $user = $security->getUser();
        $day = new \DateTimeImmutable($date);

        $content = trim((string) $request->request->get('content'));

        $entry = $repository->findOneBy([
            'user' => $user,
            'date' => $day,
        ]);

        if (!$entry) {
            $entry = new JournalEntry();
            $entry->setUser($user);
            $entry->setDate($day);
            $entry->setCreatedAt(new \DateTimeImmutable());
        }

        $entry->setContent($content ?: null);
        $entry->setUpdatedAt(new \DateTimeImmutable());

        $em->persist($entry);
        $em->flush();

        // Turbo любит redirect
        return $this->redirectToRoute('journal_show', [
            'date' => $day->format('Y-m-d'),
        ]);
    }
}