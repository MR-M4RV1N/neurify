<?php

namespace App\Service;

use App\Repository\LikeRepository;
use App\Repository\BookmarkRepository;
use App\Repository\CommentRepository;

class EventService
{
    private $likeRepository;
    private $bookmarkRepository;
    private $commentRepository;

    public function __construct(
        LikeRepository $likeRepository,
        BookmarkRepository $bookmarkRepository,
        CommentRepository $commentRepository
    ) {
        $this->likeRepository = $likeRepository;
        $this->bookmarkRepository = $bookmarkRepository;
        $this->commentRepository = $commentRepository;
    }

    public function enrichEvents(iterable $events, $user): array
    {
        $likedEvents = $this->likeRepository->findLikedIdsByUserAndType($user, 'event');
        $bookmarkedEvents = $this->bookmarkRepository->findBookmarkedEventIdsByUser($user);

        foreach ($events as $event) {
            if (!$event instanceof \App\Entity\Event) {
                continue; // пропускаем всё, что не Event
            }

            $event->likeCount = $this->likeRepository->countLikesFor('event', $event->getId());
            $event->bookmarkCount = $this->bookmarkRepository->countBookmarks($event);
            $event->commentCount = $this->commentRepository->countComments($event);
        }

        return [
            'events' => $events,
            'likedEvents' => $likedEvents,
            'bookmarkedEvents' => $bookmarkedEvents,
        ];
    }

    public function mergeAndSortEventAndMatrix(array $pictureRecords, array $simpleRecords, array $events, array $matrixMaps): array
    {
        foreach ($pictureRecords as $picture) {
            $picture->feedType = 'picture';
        }
        foreach ($simpleRecords as $simple) {
            $simple->feedType = 'simple';
        }
        foreach ($events as $event) {
            $event->feedType = 'event';
        }
        foreach ($matrixMaps as $map) {
            $map->feedType = 'matrix';
        }

        $combined = array_merge($pictureRecords, $simpleRecords, $events, $matrixMaps);

        usort($combined, function ($a, $b) {
            $aDate = ($a instanceof \App\Entity\Event)
                ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $a->getDate()->format('Y-m-d') . ' ' . $a->getTime()->format('H:i:s'))
                : $a->getCreatedAt();

            $bDate = ($b instanceof \App\Entity\Event)
                ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $b->getDate()->format('Y-m-d') . ' ' . $b->getTime()->format('H:i:s'))
                : $b->getCreatedAt();

            return $bDate <=> $aDate;
        });

        return $combined;
    }
}
