<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\QueryBuilder;

class UserFilterService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Фильтрует пользователей по языку.
     *
     * @param string|null $currentLang Текущий язык пользователя
     * @return QueryBuilder
     */
    public function filterByLanguage(?string $currentLang): QueryBuilder
    {
        $queryBuilder = $this->userRepository->createUsersProposalQueryBuilder();

        if ($currentLang !== null) {
            $queryBuilder->andWhere('u.lang = :lang')
                ->setParameter('lang', $currentLang);
        }

        return $queryBuilder;
    }

    /**
     * Фильтрует пользователей по стране (Латвия видит всех, остальные не видят латышей).
     *
     * @param string|null $currentCountry Текущая страна пользователя
     * @return QueryBuilder
     */
    public function filterByCountry(?string $currentCountry): QueryBuilder
    {
        $queryBuilder = $this->userRepository->createUsersProposalQueryBuilder();

        if ($currentCountry !== 'lv') {
            $queryBuilder->andWhere('u.country != :lv')
                ->setParameter('lv', 'lv');
        }

        return $queryBuilder;
    }

    public function getFilteredUsersByIds(array $allowedIds): QueryBuilder
    {
        $queryBuilder = $this->userRepository->createUsersProposalQueryBuilder();

        // Если список ID пустой — пустой результат
        if (empty($allowedIds)) {
            $queryBuilder->andWhere('1 = 0');
            return $queryBuilder;
        }

        // IN (...)
        $queryBuilder->andWhere($queryBuilder->expr()->in('u.id', $allowedIds));

        // Генерируем CASE WHEN
        $caseParts = [];
        $position = 1;

        foreach ($allowedIds as $id) {
            // CASE WHEN u.id = 102 THEN 1
            $caseParts[] = "WHEN u.id = {$id} THEN {$position}";
            $position++;
        }

        // ELSE в конце, чтобы неизвестные ID всегда были внизу
        $caseSql = '(CASE ' . implode(' ', $caseParts) . ' ELSE 999 END) AS HIDDEN sort_order';

        // Добавляем сортировку
        $queryBuilder
            ->addSelect($caseSql)
            ->addOrderBy('sort_order', 'ASC');

        return $queryBuilder;
    }
}
