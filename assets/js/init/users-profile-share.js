export function initProfileShare() {
    document.addEventListener('DOMContentLoaded', () => {
        // Копирование ссылки
        document.querySelectorAll('[data-action="copy-link"]').forEach(button => {
            button.addEventListener('click', () => {
                const link = button.dataset.link;
                navigator.clipboard.writeText(link)
                    .then(() => alert(`Ссылка скопирована: ${link}`))
                    .catch(() => fallbackCopy(link));
            });
        });

        // Поделиться ссылкой
        document.querySelectorAll('[data-action="share-link"]').forEach(button => {
            button.addEventListener('click', () => {
                const link = button.dataset.link;
                const username = button.dataset.username;

                if (navigator.share) {
                    navigator.share({
                        title: `Профиль пользователя ${username}`,
                        text: `Посмотрите профиль пользователя ${username} на Neurify`,
                        url: link
                    }).catch(error => console.error('Ошибка при попытке поделиться:', error));
                } else {
                    navigator.clipboard.writeText(link)
                        .then(() => alert(`Ссылка скопирована: ${link}`))
                        .catch(() => fallbackCopy(link));
                }
            });
        });

        // Запасной способ копирования (для старых браузеров)
        function fallbackCopy(text) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            alert(`Ссылка скопирована: ${text}`);
        }
    });
}