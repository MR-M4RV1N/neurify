<?php

namespace App\Consulting\Model\Swot;

class SwotResultNormalizer
{
    /**
     * Нормализует и защищает SWOT-ответ AI
     *
     * Ожидаемый формат:
     * {
     *   focus: string,
     *   analysis: {
     *     strengths: string[],
     *     weaknesses: string[],
     *     opportunities: string[],
     *     threats: string[]
     *   },
     *   recommendations: string[],
     *   summary: string
     * }
     */
    public function normalize(array $raw): array
    {
        // Базовая структура по умолчанию
        $result = [
            'focus' => '',
            'analysis' => [
                'strengths'    => [],
                'weaknesses'   => [],
                'opportunities'=> [],
                'threats'      => [],
            ],
            'recommendations' => [],
            'summary' => '',
        ];

        if (!$raw) {
            return $this->applyFallback($result);
        }

        // focus
        if (isset($raw['focus']) && is_string($raw['focus'])) {
            $result['focus'] = trim($raw['focus']);
        }

        // analysis blocks
        if (isset($raw['analysis']) && is_array($raw['analysis'])) {
            foreach (['strengths','weaknesses','opportunities','threats'] as $key) {
                if (isset($raw['analysis'][$key]) && is_array($raw['analysis'][$key])) {
                    $result['analysis'][$key] = $this->cleanStringArray($raw['analysis'][$key], 2);
                }
            }
        }

        // recommendations
        if (isset($raw['recommendations']) && is_array($raw['recommendations'])) {
            $result['recommendations'] = $this->cleanStringArray($raw['recommendations'], 3);
        }

        // summary
        if (isset($raw['summary']) && is_string($raw['summary'])) {
            $result['summary'] = trim($raw['summary']);
        }

        return $this->applyFallback($result);
    }

    // ======================
    // Helpers
    // ======================

    /**
     * Чистит массив строк и убирает пустые значения
     */
    private function cleanStringArray(array $items, int $min): array
    {
        $out = [];

        foreach ($items as $item) {
            if (is_string($item)) {
                $t = trim($item);
                if ($t !== '') {
                    $out[] = $t;
                }
            }
        }

        // если AI дал меньше, чем нужно — дополняем заглушками
        while (count($out) < $min) {
            $out[] = '—';
        }

        return $out;
    }

    /**
     * Гарантирует, что результат всегда пригоден для UI и задач
     */
    private function applyFallback(array $data): array
    {
        if ($data['focus'] === '') {
            $data['focus'] = 'Strategic self-assessment and prioritization';
        }

        if ($data['summary'] === '') {
            $data['summary'] =
                'This analysis highlights the current balance between internal factors and external conditions. ' .
                'The main goal is to focus on realistic steps that strengthen stability and reduce risks.';
        }

        if (count($data['recommendations']) < 3) {
            $data['recommendations'] = array_slice(
                array_merge(
                    $data['recommendations'],
                    [
                        'Review your strengths and decide how to apply them more consistently.',
                        'Identify one key weakness and define a concrete step to address it.',
                        'Monitor external opportunities and choose one to explore actively.',
                    ]
                ),
                0,
                3
            );
        }

        return $data;
    }
}