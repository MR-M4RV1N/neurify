<?php

namespace App\Data;

class MethodologyData
{
    private static $smallSteps = [
        'ru' => "Решающим является не объём усилий, а правильно найденное малое действие, которое запускает остальное почти автоматически.\n\nТы намеренно уменьшаешь старт до абсурда: 2 минуты, 1 файл, 1 строка кода. Если действие сделано достаточно маленьким и правильным, мозг перестаёт сопротивляться — и 99% работы происходит само. Не потому что стало легче, а потому что исчез барьер входа.",
        'en' => "Crucial is not the amount of effort, but the correctly found small action that triggers the rest almost automatically.\n\nYou intentionally reduce the start to absurdity: 2 minutes, 1 file, 1 line of code. If the action is made small enough and correct, the brain stops resisting — and 99% of the work happens by itself. Not because it became easier, but because the entry barrier disappeared.",
        'lv' => "Izšķirošs nav pūļu apjoms, bet pareizi atrasta mazā darbība, kas pārējo palaiž gandrīz automātiski.\n\nTu apzināti samazini startu līdz absurdam: 2 minūtes, 1 fails, 1 koda rinda. Ja darbība ir pietiekami maza un pareiza, smadzenes pārstāj pretoties — un 99% darba notiek pats no sevis. Ne tāpēc, ka kļuva vieglāk, bet tāpēc, ka pazuda ieejas barjera.",
    ];

    public static function getSmallStepsDescription(string $lang): string
    {
        return self::$smallSteps[$lang] ?? self::$smallSteps['en'];
    }
}
