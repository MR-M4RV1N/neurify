<?php

namespace App\Consulting\Model\Swot;

use App\Consulting\Contract\HtmlBuilderInterface;

class SwotHtmlBuilder implements HtmlBuilderInterface
{
    public function build(array $data): string
    {
        $focus = htmlspecialchars($data['focus'], ENT_QUOTES, 'UTF-8');

        $html = '';

        // --------------------------
        // Focus
        // --------------------------
        $html .= <<<HTML
<p><strong>Фокус:</strong> {$focus}</p>
HTML;

        // --------------------------
        // SWOT blocks
        // --------------------------
        $html .= '<div class="row">';

        $html .= $this->renderBlock(
            'Сильные стороны',
            $data['analysis']['strengths'],
            'text-success'
        );

        $html .= $this->renderBlock(
            'Слабые стороны',
            $data['analysis']['weaknesses'],
            'text-danger'
        );

        $html .= $this->renderBlock(
            'Возможности',
            $data['analysis']['opportunities'],
            'text-primary'
        );

        $html .= $this->renderBlock(
            'Угрозы',
            $data['analysis']['threats'],
            'text-warning'
        );

        $html .= '</div>';

        // --------------------------
        // Recommendations
        // --------------------------
        if (!empty($data['recommendations'])) {
            $html .= '<p><strong>Рекомендации:</strong></p><ul>';

            foreach ($data['recommendations'] as $rec) {
                $html .= '<li>' . htmlspecialchars($rec, ENT_QUOTES, 'UTF-8') . '</li>';
            }

            $html .= '</ul>';
        }

        return $html;
    }

    // ======================
    // Helpers
    // ======================

    private function renderBlock(string $title, array $items, string $class): string
    {
        if (empty($items)) {
            return '';
        }

        $html = <<<HTML
<div class="col-md-6 mb-3">
    <p class="{$class}"><strong>{$title}:</strong></p>
    <ul>
HTML;

        foreach ($items as $item) {
            $html .= '<li>' . htmlspecialchars($item, ENT_QUOTES, 'UTF-8') . '</li>';
        }

        $html .= '</ul></div>';

        return $html;
    }
}