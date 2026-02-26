<?php

namespace App\Data;

class FeatureData
{
    private static $features = [
        [
            'title' => [
                'ru' => 'Визуальный прогресс',
                'en' => 'Visual Progress',
                'lv' => 'Vizuālais progress',
            ],
            'subtitle' => [
                'ru' => 'Следите за балансом',
                'en' => 'See your balance',
                'lv' => 'Sekojiet līdzsvaram',
            ],
            'text' => [
                'ru' => 'Соберите 7 записей и увидите результаты своей недели, визуализированные в виде Колеса Баланса Жизни.',
                'en' => 'Collect 7 entries and see your weekly results visualized as the Wheel of Life balance.',
                'lv' => 'Sakrājiet 7 ierakstus un ieraugiet savas nedēļas rezultātus, kas vizualizēti kā Dzīves līdzsvara ritenis.',
            ],
            'image' => '/images/wheel-and-metrics.png',
            'order_md' => '',
        ],
        [
            'title' => [
                'ru' => 'Продвинутые графики',
                'en' => 'Advanced Charts',
                'lv' => 'Uzlabotas diagrammas',
            ],
            'subtitle' => [
                'ru' => 'Понимайте свою энергию',
                'en' => 'Understand your energy',
                'lv' => 'Izprotiet savu enerģiju',
            ],
            'text' => [
                'ru' => 'Погрузитесь глубже в свое психологическое состояние с помощью детальных графиков, таких как Эго-состояния и отслеживание эмоций.',
                'en' => 'Dive deeper into your psychological state with detailed charts like Ego States and emotion tracking.',
                'lv' => 'Iedziļinieties savā psiholoģiskajā stāvoklī ar detalizētām diagrammām, piemēram, Ego stāvokļiem un emociju izsekošanu.',
            ],
            'image' => '/images/advanced-diagram.png',
            'order_md' => 'order-md-2',
        ],
        [
            'title' => [
                'ru' => 'Neurify Master',
                'en' => 'Neurify Master',
                'lv' => 'Neurify Master',
            ],
            'subtitle' => [
                'ru' => 'Помощь в реальном времени',
                'en' => 'Real-time assistance',
                'lv' => 'Palīdzība reāllaikā',
            ],
            'text' => [
                'ru' => 'Нужен совет прямо сейчас? Наш умный ИИ-ассистент всегда доступен в лайв-чате, чтобы обсудить ваши мысли или помочь ориентироваться на платформе.',
                'en' => 'Need advice right now? Our smart AI assistant is always available in the live chat to discuss your thoughts or help navigate the platform.',
                'lv' => 'Nepieciešams padoms tūlīt? Mūsu viedais AI asistents vienmēr ir pieejams tiešsaistes tērzēšanā, lai apspriestu jūsu domas vai palīdzētu orientēties platformā.',
            ],
            'image' => '/images/NF-M-CHAT.png',
            'order_md' => '',
        ],
    ];

    public static function getAll(string $lang): array
    {
        $result = [];
        foreach (self::$features as $f) {
            $result[] = [
                'title' => $f['title'][$lang] ?? $f['title']['en'],
                'subtitle' => $f['subtitle'][$lang] ?? $f['subtitle']['en'],
                'text' => $f['text'][$lang] ?? $f['text']['en'],
                'image' => $f['image'],
                'order_md' => $f['order_md'],
            ];
        }
        return $result;
    }
}
