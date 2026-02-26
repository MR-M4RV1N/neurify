import introJs from 'intro.js';

export function initIntro() {
    const introElement = document.getElementById('intro-data');
    if (!introElement) {
        return;
    }

    const userJustRegistered = introElement.dataset.userJustRegistered === 'true';
    if (!userJustRegistered) {
        return;
    }

    const intro = introJs();

    intro.setOptions({
        steps: [
            {
                element: '#neurify-gpt',
                intro: 'Тут нейросеть подскажет вам как пользоваться платформой.',
                position: 'bottom'
            },
            {
                element: '#platform-challenges',
                intro: 'Тут вы узнаете что делать на платформе.',
                position: 'bottom'
            },
            {
                element: '#week-challenge',
                intro: 'Тут каждую неделю выходят новые задания.',
                position: 'bottom'
            },
            {
                element: '#mentors',
                intro: 'Тут наставники публикуют тематические задания.',
                position: 'bottom'
            },
            {
                element: '#user-level',
                intro: 'Тут отображается ваш уровень.',
                position: 'bottom'
            },
            {
                element: '#user-selected-artisan',
                intro: 'Тут вы можете создать портфолио.',
                position: 'bottom'
            },
        ],
        showProgress: true,
        showBullets: true,
        nextLabel: 'Дальше',
        prevLabel: 'Назад',
        doneLabel: 'Готово',
        overlayOpacity: 0.5
    });

    intro.start();
}