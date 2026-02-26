<?php

namespace App\Consulting\Contract;

use App\Entity\ConsultingInsight;
use App\Entity\User;

interface ConsultingModelInterface
{
    /**
     * Уникальный ключ модели.
     * Примеры: "dreyfus", "swot", "life_balance"
     */
    public function getKey(): string;

    /**
     * Проверка, можно ли применять модель к пользователю.
     * Например:
     * - SWOT заполнен
     * - Колесо баланса оценено
     */
    public function supports(User $user): bool;

    /**
     * Генерация консультационного результата.
     * Возвращает структурированный массив,
     * который дальше может быть:
     * - сохранён
     * - отображён
     * - превращён в задачи
     */
    public function generate(User $user, string $lang): ConsultingInsight;
}