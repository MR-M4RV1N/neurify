<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use HTMLPurifier;
use HTMLPurifier_Config;

class AiTaskService
{
    private $http;
    private $deepseekApiKey;
    private $purifier;

    public function __construct(HttpClientInterface $http, string $deepseekApiKey)
    {
        $this->http = $http;
        $this->deepseekApiKey = $deepseekApiKey;

        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,strong,em,b,i,ul,li');
        $config->set('URI.Disable', true);
        $this->purifier = new HTMLPurifier($config);
    }

    public function generateFullProgressComment(
        User $user,
        array $eventsAll,
        array $completed,
        array $notCompleted
    ): string
    {
        $lang = $user->getLang() ?: 'ru';

        $all = $this->formatEvents($eventsAll);
        $done = $this->formatEvents($completed);
        $pending = $this->formatEvents($notCompleted);

        $completedCount = count($completed);
        $notCompletedCount = count($notCompleted);

        $prompt = <<<PROMPT
Neurify.life progress analysis.

Language: {$lang}.

All events in current challenge folder:
{$all}

Completed events:
{$done}

Not completed events:
{$pending}

Summary:
Completed: {$completedCount}
Not completed: {$notCompletedCount}

Write compact HTML (<p>, <ul>, <li>, <b>, <i>, <strong>, <em>). No links.

Structure:
<p>Insight about the balance between completed and pending tasks (2–3 sentences).</p>
<p>Why some tasks were completed and some not (1–3 sentences).</p>
<p>Next steps (1 sentence).</p>
<ul>
 <li>Step 1 (≤25 words)</li>
 <li>Step 2 (≤25 words)</li>
 <li>Step 3 (≤25 words)</li>
</ul>

PROMPT;

        return $this->purify($this->callApi($prompt));
    }
}