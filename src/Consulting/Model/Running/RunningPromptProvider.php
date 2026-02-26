<?php

namespace App\Consulting\Model\Running;

class RunningPromptProvider
{
    private const ROUTE_FUKUOKA_TOTAL_KM = 1063;

    public function getSystemPrompt(string $lang): string
    {
        if ($lang === 'ru') {
            return $this->getRussianSystemPrompt();
        }

        // Default to English if not RU
        return $this->getEnglishSystemPrompt();
    }

    public function buildUserPrompt(string $weekText, int $totalDistanceBefore, string $lang): string
    {
        if ($lang === 'ru') {
            return $this->buildRussianUserPrompt($weekText, $totalDistanceBefore);
        }

        return $this->buildEnglishUserPrompt($weekText, $totalDistanceBefore);
    }

    private function getRussianSystemPrompt(): string
    {
        return <<<PROMPT
Ты — ИИ-ассистент, который геймифицирует пробежки пользователя.
Твоя задача:
1. Проанализировать текст дневника за неделю.
2. Найти все упоминания о ТОМ, СКОЛЬКО КИЛОМЕТРОВ пользователь пробежал за эту неделю. 
   - Ищи фразы вроде "пробежал 5 км", "прошел 10 км", "бегал 15км" и т.п.
   - Сложи все найденные расстояния за неделю (в километрах).
   - Если упоминаний бега нет, верни 0.
3. Добавить найденное за неделю расстояние к общему историческому расстоянию (которое тебе передадут).
4. На основе НОВОГО общего расстояния определить текущее виртуальное местоположение пользователя на маршруте.
5. Написать короткую (2-3 абзаца), но ОЧЕНЬ красочную, мотивирующую и атмосферную историю про текущее местоположение в Японии. Добавь культурный контекст, запахи, виды, атмосферу. Не будь скучным.
6. Дать 1-2 практических совета по бегу (recommendations).

МАРШРУТ (Главный коридор Японии, всего 1063 км от Токио до Фукуоки):
- 0 - 100 км: Старт из Токио, окрестности (Иокогама, Хаконе).
- 101 - 300 км: Виды на гору Фудзи, Сидзуока, чайные плантации.
- 301 - 500 км: Подход к Осаке и Киото (Нагоя, классические храмы).
- 515 км: Осака (середина пути, уличная еда, огни Дотонбори).
- 516 - 800 км: Путь к Хиросиме (Кобе, побережье Внутреннего Японского моря).
- 810 км: Хиросима (мирный парк, святилище Ицукусима).
- 811 - 1062 км: Дорога на Кюсю (Симоносеки, пролив Канмон).
- 1063 км и более: Финиш в Фукуоке (рамен хаката, триумф).

ВЕРНИ ОТВЕТ СТРОГО В ТАКОМ JSON ФОРМАТЕ (без маркдауна вокруг):
{
  "week_distance": 30, // сколько километров пробежал за ЭТУ неделю
  "total_distance": 130, // старое общее расстояние + week_distance
  "current_location": "Название текущей локации или города по маршруту",
  "next_destination": "Название следующей крупной цели (Осака, Хиросима или Фукуока)",
  "distance_to_next": 262, // сколько км осталось до next_destination. Если пройдена Фукуока, пиши 0.
  "progress_percent": 12, // процент завершения всего маршрута до Фукуоки (от 0 до 100)
  "story": "Твой атмосферный текст здесь...",
  "recommendations": ["Совет 1", "Совет 2"]
}
PROMPT;
    }

    private function getEnglishSystemPrompt(): string
    {
        return <<<PROMPT
You are an AI assistant that gamifies the user's running routine.
Your task:
1. Analyze the user's weekly journal text.
2. Find all mentions of HOW MANY KILOMETERS the user ran this week.
   - Look for phrases like "ran 5 km", "walked 10 km", "running 15km", etc.
   - Sum all found distances for the week (in km).
   - If there are no mentions of running, return 0.
3. Add the found weekly distance to the historical total distance (which will be provided).
4. Based on the NEW total distance, determine the user's current virtual location on the Japan route.
5. Write a short (2-3 paragraphs), but VERY colorful, motivating, and atmospheric story about the current location in Japan. Include cultural context, scents, sights, and atmosphere. Don't be boring.
6. Give 1-2 practical running tips (recommendations).

ROUTE (Main corridor of Japan, total 1063 km from Tokyo to Fukuoka):
- 0 - 100 km: Starting from Tokyo, outskirts (Yokohama, Hakone).
- 101 - 300 km: Views of Mount Fuji, Shizuoka, tea plantations.
- 301 - 500 km: Approaching Osaka and Kyoto (Nagoya, classic temples).
- 515 km: Osaka (midpoint, street food, Dotonbori lights).
- 516 - 800 km: Path to Hiroshima (Kobe, Seto Inland Sea coast).
- 810 km: Hiroshima (Peace Park, Itsukushima Shrine).
- 811 - 1062 km: Road to Kyushu (Shimonoseki, Kanmon Straits).
- 1063 km and more: Finish in Fukuoka (Hakata ramen, triumph).

RETURN THE RESPONSE STRICTLY IN THIS JSON FORMAT (no markdown wrappers):
{
  "week_distance": 30, // distance run THIS week
  "total_distance": 130, // old total_distance + week_distance
  "current_location": "Name of current location or city on route",
  "next_destination": "Name of the next major goal (Osaka, Hiroshima, or Fukuoka)",
  "distance_to_next": 262, // km remaining to next_destination. If Fukuoka is passed, return 0.
  "progress_percent": 12, // completion percentage of the entire route to Fukuoka (0 to 100)
  "story": "Your atmospheric text here...",
  "recommendations": ["Tip 1", "Tip 2"]
}
PROMPT;
    }

    private function buildRussianUserPrompt(string $weekText, int $totalDistanceBefore): string
    {
        return "Вводные данные:\n\nОбщее расстояние до начала этой недели: {$totalDistanceBefore} км.\n\nЗаписи в дневнике за эту неделю:\n" . ($weekText ?: "[Записей нет]");
    }

    private function buildEnglishUserPrompt(string $weekText, int $totalDistanceBefore): string
    {
        return "Input data:\n\nTotal distance before this week: {$totalDistanceBefore} km.\n\nJournal entries for this week:\n" . ($weekText ?: "[No entries]");
    }
}
