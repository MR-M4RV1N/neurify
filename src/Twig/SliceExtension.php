<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\Markup;

class SliceExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('html_slice', [$this, 'htmlSlice']),
        ];
    }

    public function htmlSlice($value, $length): Markup
    {
        // Оборачиваем входные данные в базовые HTML теги
        $html = mb_convert_encoding('<html><body>' . $value . '</body></html>', 'HTML-ENTITIES', 'UTF-8');

        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $newHtml = '';
        $len = 0;

        $body = $dom->getElementsByTagName('body')->item(0);

        if (!$body) {
            // Если body не существует, возвращаем пустой Markup
            return new Markup('', 'UTF-8');
        }

        foreach ($body->childNodes as $child) {
            $content = $dom->saveHTML($child);
            $contentLength = mb_strlen(strip_tags($content));

            if ($len + $contentLength > $length) {
                $newHtml .= mb_substr(strip_tags($content), 0, $length - $len) . '...';
                break;
            } else {
                $newHtml .= $content;
                $len += $contentLength;
            }
        }

        return new Markup($newHtml, 'UTF-8');
    }
}
