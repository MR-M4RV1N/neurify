<?php

namespace App\Data;

class DescriptionData
{
    private static $descriptions = [
        'ru' => "Я простой участник проекта Neurify. Хочу использовать платформу как полотно, на котором я нарисую потрясающую картину при помощи маленьких и больших испытаний.",
        'en' => "I am a simple participant of the Neurify project. I want to use the platform as a canvas on which I will paint an amazing picture with small and big challenges.",
        'lv' => "Es esmu vienkāršs projekta Neurify dalībnieks. Vēlos izmantot šo platformu kā audeklu, uz kura uzzīmēšu satriecošu gleznu izmantojot mazus un lielus izaicinājumus.",
    ];
    private static $artisans = [
        'ru' => "Простой пользователь",
        'en' => "Simple user",
        'lv' => "Parasts lietotājs",
    ];

    public static function getDescription(string $lang): string
    {
        // Возвращаем описание на основе языка или значение по умолчанию ('en')
        return isset(self::$descriptions[$lang]) ? self::$descriptions[$lang] : self::$descriptions['en'];
    }

    public static function getArtisan(string $lang): string
    {
        // Возвращаем описание на основе языка или значение по умолчанию ('en')
        return isset(self::$artisans[$lang]) ? self::$artisans[$lang] : self::$artisans['en'];
    }
}
