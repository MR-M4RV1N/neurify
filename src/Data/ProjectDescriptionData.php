<?php

namespace App\Data;

class ProjectDescriptionData
{
    private static $benefitsDiary = [
        'ru' => "Ведение дневника помогает упорядочить мысли, снизить уровень стресса и лучше понять себя. Регулярно записывая свой опыт, человек начинает замечать повторяющиеся модели поведения и эмоций, что позволяет принимать более осознанные решения. Дневник улучшает память, развивает самоанализ и помогает фиксировать прогресс — как в маленьких ежедневных шагах, так и в значимых достижениях. Он становится безопасным пространством для честности с собой и инструментом внутреннего роста.",
        'en' => "Keeping a journal helps organize thoughts, reduce stress, and gain a deeper understanding of oneself. By regularly writing about personal experiences, people begin to notice recurring patterns in their behavior and emotions, which leads to more conscious decision-making. Journaling improves memory, encourages self-reflection, and helps track progress — from small daily steps to meaningful achievements. It becomes a safe space for honesty with oneself and a tool for personal growth.",
        'lv' => "Dienasgrāmatas rakstīšana palīdz sakārtot domas, mazināt stresu un labāk izprast sevi. Regulāri pierakstot pieredzi, cilvēks pamana atkārtojošos modeļus savā uzvedībā un emocijās, kas ļauj pieņemt apzinātākus lēmumus. Dienasgrāmata uzlabo atmiņu, veicina pašrefleksiju un palīdz fiksēt progresu — gan ikdienas sīkos soļus, gan nozīmīgus sasniegumus. Tā kļūst par drošu telpu godīgumam ar sevi un iekšējās izaugsmes rīku.",
    ];

    private static $benefitsAiComments = [
        'ru' => "AI-советы помогают взглянуть на записи дневника со стороны и превратить размышления в конкретные действия. Искусственный интеллект анализирует эмоции, темы и повторяющиеся модели поведения, предлагая ясные и нейтральные рекомендации. Это позволяет лучше понять свои привычки, направления мышления и динамику развития, а также постепенно вносить изменения, опираясь на собственный опыт.",
        'en' => "AI advice helps users look at their journal entries from an external perspective and turn reflections into actionable steps. Artificial intelligence analyzes emotions, themes, and recurring behavior patterns, offering clear and neutral recommendations. This supports a deeper understanding of personal habits, thinking styles, and growth dynamics, while helping users make gradual, intentional changes based on their own experiences.",
        'lv' => "AI padomi palīdz paskatīties uz dienasgrāmatas ierakstiem no malas un pārvērst pārdomas konkrētās rīcībās. Mākslīgais intelekts analizē emocijas, tēmas un atkārtojošos uzvedības modeļus, piedāvājot skaidrus un neitrālus ieteikumus. Tas palīdz labāk izprast savus ieradumus, domāšanas virzienus un attīstības dinamiku, kā arī soli pa solim virzīties uz pārmaiņām, balstoties uz paša pieredzi.",
    ];

    private static $benefitsSmallSteps = [
        'ru' => "Подход малых шагов показывает, что решающим является не объём усилий, а правильно найденное маленькое действие, которое запускает всё остальное почти автоматически. Ты намеренно уменьшаешь старт до абсурда — 2 минуты, 1 файл, 1 строка кода. Если действие достаточно маленькое и точное, мозг перестаёт сопротивляться, и процесс начинает развиваться сам. Не потому, что стало легче, а потому, что исчез входной барьер.",
        'en' => "The small-steps approach shows that the key factor is not the amount of effort, but a well-chosen small action that triggers everything else almost automatically. You intentionally reduce the start to something absurd — 2 minutes, 1 file, 1 line of code. When an action is small and precise enough, the brain stops resisting and momentum builds on its own. Not because it becomes easier, but because the entry barrier disappears.",
        'lv' => "Mazo soļu pieeja balstās uz principu, ka izšķirošs nav pūļu apjoms, bet pareizi atrasta mazā darbība, kas iedarbina pārējo gandrīz automātiski. Tu apzināti samazini startu līdz absurdam — 2 minūtes, 1 fails, 1 koda rinda. Ja darbība ir pietiekami maza un precīza, smadzenes pārstāj pretoties, un process sāk kustēties pats no sevis. Nevis tāpēc, ka kļuva vieglāk, bet tāpēc, ka pazuda ieejas barjera.",
    ];

    private static $benefitsChallenges = [
        'ru' => "Накопление вызовов помогает осознанно замечать и сохранять моменты, в которых ты выходил за пределы привычного. Фиксируя вызовы, ты начинаешь видеть траекторию своей смелости, усилий и роста во времени. Даже небольшие вызовы накапливаются, формируя доказательства самому себе, что ты способен действовать, меняться и справляться со сложными ситуациями. Такая коллекция укрепляет уверенность в себе, поддерживает мотивацию и превращает опыт в личный капитал.",
        'en' => "Collecting challenges helps consciously notice and preserve moments when you stepped outside your comfort zone. By recording challenges, you can see the trajectory of your courage, effort, and growth over time. Even small challenges accumulate, becoming evidence that you are capable of acting, changing, and handling difficult situations. This collection strengthens self-confidence, sustains motivation, and turns experience into personal capital.",
        'lv' => "Izaicinājumu krāšana palīdz apzināti pamanīt un saglabāt mirkļus, kuros tu izgāji ārpus ierastā. Fiksējot izaicinājumus, tu redzi savu drosmes, piepūles un izaugsmes trajektoriju laika gaitā. Pat nelieli izaicinājumi uzkrājas, veidojot pierādījumus sev, ka spēj rīkoties, mainīties un tikt galā ar sarežģītām situācijām. Šī kolekcija stiprina pašapziņu, motivē turpināt un pārvērš pieredzi personīgā kapitālā.",
    ];

    public static function getDiaryDescription(string $lang): string
    {
        return self::$benefitsDiary[$lang] ?? self::$benefitsDiary['en'];
    }

    public static function getAiCommentsDescription(string $lang): string
    {
        return self::$benefitsAiComments[$lang] ?? self::$benefitsAiComments['en'];
    }

    public static function getSmallStepsDescription(string $lang): string
    {
        return self::$benefitsSmallSteps[$lang] ?? self::$benefitsSmallSteps['en'];
    }

    public static function getChallengesDescription(string $lang): string
    {
        return self::$benefitsChallenges[$lang] ?? self::$benefitsChallenges['en'];
    }

    public static function getAll(string $lang): array
    {
        return [
            'diary' => self::getDiaryDescription($lang),
            'ai_comments' => self::getAiCommentsDescription($lang),
            'small_steps' => self::getSmallStepsDescription($lang),
            'challenges' => self::getChallengesDescription($lang),
        ];
    }
}
