<?php

namespace App\Data;

class LandingTestimonialData
{
    private static $testimonials = [
        [
            'name' => 'Alex',
            'avatar' => '/images/common/avatar-1.jpeg',
            'text' => [
                'ru' => 'Наконец-то дневник, который реально отвечает мне. Анализ моей недели по методу Эрика Берна помог мне понять, почему я выгорал на работе.',
                'en' => 'Finally a diary that actually talks back to me. The analysis of my week using Eric Berne\'s methods helped me understand why I was burning out at work.',
                'lv' => 'Beidzot dienasgrāmata, kas man reāli atbild. Manas nedēļas analīze pēc Ērika Berna metodes palīdzēja man saprast, kāpēc es izdegu darbā.',
            ]
        ],
        [
            'name' => 'Maria',
            'avatar' => '/images/common/avatar-2.jpeg',
            'text' => [
                'ru' => 'Я выбрала Карла Юнга своим ИИ-наставником. Читать его интерпретацию моих повседневных трудностей было потрясающе, это дало мне новую перспективу.',
                'en' => 'I chose Carl Jung as my AI mentor. Reading his interpretation of my daily struggles was mind-blowing and gave me a fresh perspective.',
                'lv' => 'Es izvēlējos Karlu Jungu par savu AI mentoru. Lasīt viņa interpretāciju par manām ikdienas grūtībām bija pārsteidzoši, tas deva man jaunu perspektīvu.',
            ]
        ],
        [
            'name' => 'David (Biohacker)',
            'avatar' => '/images/common/avatar-3.jpeg',
            'text' => [
                'ru' => 'Записывать свои дни здесь так просто. ИИ в режиме биохакинга заметил связь между моими поздними ужинами и плохой концентрацией на следующий день.',
                'en' => 'Logging my days here is so simple. The biohacking AI mode spotted a correlation between my late dinners and poor focus the next day.',
                'lv' => 'Fiksēt savas dienas šeit ir tik vienkārši. AI biohakinga režīmā pamanīja sakarību starp manām vēlajām vakariņām un sliktu koncentrēšanos nākamajā dienā.',
            ]
        ]
    ];

    public static function getAll(string $lang): array
    {
        $result = [];
        foreach (self::$testimonials as $t) {
            $result[] = [
                'name' => $t['name'],
                'avatar' => $t['avatar'],
                'text' => $t['text'][$lang] ?? $t['text']['en'],
            ];
        }
        return $result;
    }
}
