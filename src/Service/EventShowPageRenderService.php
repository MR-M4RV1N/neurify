<?php
namespace App\Service;

use App\Entity\Bookmark;
use App\Repository\BookmarkRepository;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use App\Repository\EventSharedRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ReformulationRecordRepository;

class EventShowPageRenderService extends AbstractController
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var ReformulationRecordRepository
     */
    private $reformRepo;

    /**
     * @var EventSharedRepository
     */
    private $sharedRepo;

    public function __construct(
        EventRepository $eventRepository,
        CommentRepository $commentRepository,
        BookmarkRepository $bookmarkRepository,
        EventSharedRepository $sharedRepo,
        ReformulationRecordRepository $reformRepo
    )
    {
        $this->eventRepository = $eventRepository;
        $this->commentRepository = $commentRepository;
        $this->bookmarkRepository = $bookmarkRepository;
        $this->sharedRepo = $sharedRepo;
        $this->reformRepo = $reformRepo;
    }

    /**
     * Render the event page with comments.
     *
     * @param Request $request
     * @param int $id
     * @param string $template
     * @param bool $onlyPublic
     * @return Response
     */
    public function renderEventPage(Request $request, int $id, string $template, bool $onlyPublic = false): Response
    {
        // Условия выборки события
        $criteria = ['id' => $id];
        if ($onlyPublic) {
            $criteria['public'] = true;
        }

        $event = $this->eventRepository->findOneBy($criteria);
        if (!$event) {
            throw $this->createNotFoundException('The event does not exist');
        }

        // Получение комментариев
        $queryBuilder = $this->commentRepository->createCommentsQueryBuilder($event->getId());
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(12);
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1));

        $isAccepted = (bool) $this->bookmarkRepository->findOneBy([
            'user' => $this->getUser(),
            'event' => $event->getId()
        ]);

        // Если пользователь — владелец события
        $eventShared = null;
        if ($this->getUser() && $this->getUser()->getId() === $event->getUser()->getId()) {
            $eventShared = $this->sharedRepo->findOneBy(['event' => $event]);
        }

        // ================================
        // ➤ ReformulationRecord - alternaticve
        // ================================
        $reformRecord = null;
        $alternatives = [];

        if ($this->getUser() && $this->getUser()->getId() === $event->getUser()->getId()) {

            // Берём последнюю запись по этому событию
            $reformRecord = $this->reformRepo->findOneBy(
                ['event' => $event, 'method' => 'alternative'],
                ['createdAt' => 'DESC']
            );

            if ($reformRecord) {
                $alternatives = $reformRecord->getAlternatives() ?? [];
            }
        }

        // ================================
        // ➤ ReformulationRecord - advocate
        // ================================
        $advocateRecord = null;
        $critiques = [];

        if ($this->getUser() && $this->getUser()->getId() === $event->getUser()->getId()) {

            // Берём последнюю запись по этому событию
            $advocateRecord = $this->reformRepo->findOneBy(
                ['event' => $event, 'method' => 'advocate'],
                ['createdAt' => 'DESC']
            );

            if ($advocateRecord) {
                $critiques = $advocateRecord->getAlternatives() ?? [];
            }
        }

        // ================================
        // ➤ ReformulationRecord - reverse
        // ================================
        $reverseRecord = null;
        $assumptions = [];

        if ($this->getUser() && $this->getUser()->getId() === $event->getUser()->getId()) {

            // Берём последнюю запись по этому событию
            $reverseRecord = $this->reformRepo->findOneBy(
                ['event' => $event, 'method' => 'reverse'],
                ['createdAt' => 'DESC']
            );

            if ($reverseRecord) {
                $assumptions = $reverseRecord->getAlternatives() ?? [];
            }
        }

        // Рендеринг
        return $this->render($template, [
            'event'        => $event,
            'comments'     => $pagerfanta,
            'isAccepted'   => $isAccepted,
            'isGenerated'  => $eventShared !== null,
            'reformulationRecord' => $reformRecord,
            'alternatives'        => $alternatives,
            'critiques'    => $critiques,
            'assumptions'  => $assumptions,
        ]);
    }
}
