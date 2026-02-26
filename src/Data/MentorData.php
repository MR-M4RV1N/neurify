<?php

namespace App\Data;

class MentorData
{
    private static $mentors = [
        'psychology' => [
            'icon' => 'psychology',
            'title' => [
                'ru' => 'Психология',
                'en' => 'Psychology',
                'lv' => 'Psiholoģija',
            ],
            'description' => [
                'ru' => "Исследуйте свою личность через призму трансактного анализа Эрика Берна, глубинной психологии Карла Юнга и других.",
                'en' => "Explore your personality through the lens of Eric Berne's Transactional Analysis, Carl Jung's depth psychology and others.",
                'lv' => "Izpētiet savu personību ar Ērika Berna transakciju analīzi, Karla Junga dziļumpsihioloģiju un citām pieejām.",
            ],
            'bg_image' => 'woman-with-hands-together-talking-with-counselor.jpg',
            'theme_container' => 'bg-dark text-white',
            'theme_title' => 'text-white',
            'theme_text' => '',
            'theme_image_container' => 'bg-secondary',
            'icon_class' => 'text-white'
        ],
        'great_thinkers' => [
            'icon' => 'account_balance',
            'title' => [
                'ru' => 'Великие мыслители',
                'en' => 'Great Thinkers',
                'lv' => 'Dižie domātāji',
            ],
            'description' => [
                'ru' => 'Позвольте историческим философам, стратегам вроде Нассима Талеба, стоикам и другим проанализировать вашу неделю.',
                'en' => 'Let historical philosophers, strategists like Nassim Taleb, Stoics and others analyze your week.',
                'lv' => 'Ļaujiet vēstures filozofiem, tādiem stratēģiem kā Nasimam Talebam, stoiķiem un citiem analizēt jūsu nedēļu.',
            ],
            'bg_image' => 'view-ancient-greek-bust-figure.jpg',
            'theme_container' => 'bg-light',
            'theme_title' => '',
            'theme_text' => 'text-muted',
            'theme_image_container' => 'bg-white border-1 border-gray-200',
            'icon_class' => 'text-primary'
        ],
        'health' => [
            'icon' => 'favorite',
            'title' => [
                'ru' => 'Здоровье',
                'en' => 'Health',
                'lv' => 'Veselība',
            ],
            'description' => [
                'ru' => 'Улучшайте физическую и ментальную устойчивость с помощью инсайтов на основе данных о сне, энергии и восстановлении.',
                'en' => 'Improve your physical and mental resilience with data-driven insights on sleep, energy levels, and recovery.',
                'lv' => 'Uzlabojiet savu fizisko un garīgo noturību ar datos balstītām atziņām par miegu, enerģijas līmeni un atjaunošanos.',
            ],
            'bg_image' => 'patient-doing-exercise-spin-bike-gym-with-therapist.jpg',
            'theme_container' => 'bg-secondary',
            'theme_title' => 'text-white',
            'theme_text' => 'text-white',
            'theme_image_container' => 'bg-white border-1 border-gray-200',
            'icon_class' => 'text-success'
        ],
        'wealth' => [
            'icon' => 'trending_up',
            'title' => [
                'ru' => 'Богатство',
                'en' => 'Wealth',
                'lv' => 'Bagātība',
            ],
            'description' => [
                'ru' => 'Анализируйте свои финансовые привычки через мудрость Джорджа С. Клейсона («Самый богатый человек в Вавилоне»), Наполеона Хилла («Думай и богатей») и других.',
                'en' => 'Analyze your financial habits through the wisdom of George S. Clason ("The Richest Man in Babylon"), Napoleon Hill ("Think and Grow Rich"), and others.',
                'lv' => 'Analizējiet savus finanšu ieradumus caur Džordža S. Kleisona ("Bagātākais vīrs Bābelē"), Napoleona Hila ("Domā un kļūsti bagāts") un citu gudrību.',
            ],
            'bg_image' => 'man-with-banknotes-isolated-studio.jpg',
            'theme_container' => 'bg-warning text-white',
            'theme_title' => '',
            'theme_text' => '',
            'theme_image_container' => 'bg-dark',
            'icon_class' => 'text-white'
        ],
        'behavior' => [
            'icon' => 'psychology_alt',
            'title' => [
                'ru' => 'Поведение и мышление',
                'en' => 'Behavior & Mindset',
                'lv' => 'Uzvedība un domāšana',
            ],
            'description' => [
                'ru' => 'Откройте для себя поведенческие модели DISC и анализируйте паттерны мышления через MBTI.',
                'en' => 'Discover behavioral models with DISC and analyze your thinking patterns through MBTI.',
                'lv' => 'Atklājiet uzvedības modeļus ar DISC un analizējiet savus domāšanas modeļus caur MBTI.',
            ],
            'bg_image' => 'elderly-couple-retirement-home-playing-chess.jpg',
            'theme_container' => 'bg-dark text-white',
            'theme_title' => 'text-white',
            'theme_text' => '',
            'theme_image_container' => 'bg-secondary',
            'icon_class' => 'text-white'
        ],
        'virtual_running' => [
            'icon' => 'directions_run',
            'title' => [
                'ru' => 'Виртуальный бег',
                'en' => 'Virtual Running',
                'lv' => 'Virtuālā skriešana',
            ],
            'description' => [
                'ru' => 'Превратите километры в путешествие. ИИ отслеживает ваш прогресс на виртуальном маршруте по Японии — от Токио до Фукуоки.',
                'en' => 'Turn your kilometers into a journey. AI tracks your progress on a virtual route across Japan — from Tokyo to Fukuoka.',
                'lv' => 'Pārvērtiet savus kilometrus ceļojumā. AI seko līdzi jūsu progresam virtuālā maršrutā cauri Japānai — no Tokijas līdz Fukuokai.',
            ],
            'bg_image' => 'determined-female-runner-jogging-through-misty-filed-morning-copy-space.jpg',
            'theme_container' => 'bg-primary text-white',
            'theme_title' => 'text-white',
            'theme_text' => 'text-white',
            'theme_image_container' => 'bg-dark',
            'icon_class' => 'text-white'
        ],
        'your_context' => [
            'icon' => 'tips_and_updates',
            'title' => [
                'ru' => 'Свой контекст',
                'en' => 'Your Context',
                'lv' => 'Savi konteksti',
            ],
            'description' => [
                'ru' => 'Используйте собственные запросы, чтобы получать нужные ответы из вашего дневника.',
                'en' => 'Use your own custom prompts to get the answers you need from your journal.',
                'lv' => 'Izmantojiet savas pielāgotās uzvednes, lai iegūtu nepieciešamās atbildes no savas dienasgrāmatas.',
            ],
            'bg_image' => '',
            'theme_container' => 'bg-white',
            'theme_title' => '',
            'theme_text' => 'text-muted',
            'theme_image_container' => '',
            'icon_class' => 'text-danger'
        ]
    ];

    public static function getAll(string $lang): array
    {
        $result = [];
        foreach (self::$mentors as $key => $data) {
            $result[$key] = [
                'title' => $data['title'][$lang] ?? $data['title']['en'],
                'description' => $data['description'][$lang] ?? $data['description']['en'],
                'icon' => $data['icon'] ?? '',
                'bg_image' => $data['bg_image'] ?? '',
                'theme_container' => $data['theme_container'] ?? '',
                'theme_title' => $data['theme_title'] ?? '',
                'theme_text' => $data['theme_text'] ?? '',
                'theme_image_container' => $data['theme_image_container'] ?? '',
                'icon_class' => $data['icon_class'] ?? '',
            ];
        }
        return $result;
    }
}
