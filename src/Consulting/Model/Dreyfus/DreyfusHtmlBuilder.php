<?php

namespace App\Consulting\Model\Dreyfus;

use App\Consulting\Contract\HtmlBuilderInterface;

class DreyfusHtmlBuilder implements HtmlBuilderInterface
{
    public function build(array $data): string
    {
        // ожидаем уже НОРМАЛИЗОВАННЫЕ данные
        $level   = htmlspecialchars($data['level_name'], ENT_QUOTES, 'UTF-8');
        $levelNo = (int) $data['career_level'];
        $reason  = htmlspecialchars($data['reasoning'], ENT_QUOTES, 'UTF-8');

        $signalsHtml = '';
        foreach ($data['signals'] as $signal) {
            $signalsHtml .= '<li>' . htmlspecialchars($signal, ENT_QUOTES, 'UTF-8') . '</li>';
        }

        $recsHtml = '';
        foreach ($data['recommendations'] as $rec) {
            $recsHtml .= '<li>' . htmlspecialchars($rec, ENT_QUOTES, 'UTF-8') . '</li>';
        }

        return <<<HTML
<p><strong>Карьерный уровень:</strong> {$level} ({$levelNo})</p>

<p>{$reason}</p>

<p><strong>Сигналы:</strong></p>
<ul>
{$signalsHtml}
</ul>

<p><strong>Рекомендации:</strong></p>
<ul>
{$recsHtml}
</ul>
HTML;
    }
}