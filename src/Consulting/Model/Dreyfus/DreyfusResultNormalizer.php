<?php

namespace App\Consulting\Model\Dreyfus;

class DreyfusResultNormalizer
{
    /**
     * Нормализует результат Dreyfus-модели.
     * Никогда не выбрасывает исключений.
     */
    public function normalize(array $json): array
    {
        // 1. Дефолтная структура (как в AiConsultingService)
        $normalized = array_merge([
            'career_level'    => 1,
            'level_name'      => 'Novice',
            'signals'         => [],
            'reasoning'       => '',
            'recommendations' => [],
            'summary'         => '',
        ], $json);

        // 2. Гарантия типов
        if (!is_int($normalized['career_level'])) {
            $normalized['career_level'] = (int) $normalized['career_level'];
        }

        if (!is_string($normalized['level_name'])) {
            $normalized['level_name'] = 'Novice';
        }

        if (!is_array($normalized['signals'])) {
            $normalized['signals'] = [];
        }

        if (!is_array($normalized['recommendations'])) {
            $normalized['recommendations'] = [];
        }

        if (!is_string($normalized['reasoning'])) {
            $normalized['reasoning'] = '';
        }

        if (!is_string($normalized['summary'])) {
            $normalized['summary'] = '';
        }

        // 3. Минимальная санитарная очистка массивов
        $normalized['signals'] = $this->sanitizeStringArray($normalized['signals']);
        $normalized['recommendations'] = $this->sanitizeStringArray($normalized['recommendations']);

        // 4. Ограничение значений уровня (1–5)
        if ($normalized['career_level'] < 1 || $normalized['career_level'] > 5) {
            $normalized['career_level'] = 1;
            $normalized['level_name'] = 'Novice';
        }

        return $normalized;
    }

    /**
     * Очищает массив строк:
     * - убирает нестроковые значения
     * - тримит
     * - убирает пустые
     */
    private function sanitizeStringArray(array $items): array
    {
        $clean = [];

        foreach ($items as $item) {
            if (!is_string($item)) {
                continue;
            }

            $item = trim($item);

            if ($item === '') {
                continue;
            }

            $clean[] = $item;
        }

        return $clean;
    }
}