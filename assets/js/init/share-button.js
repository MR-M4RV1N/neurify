export function initShareButton() {
    const shareButtons = document.querySelectorAll('[data-share-button]');

    shareButtons.forEach((wrapper) => {
        const message = wrapper.querySelector('[data-share-message]');
        const url = wrapper.dataset.url || window.location.href;

        const button = wrapper.querySelector('button');
        if (!button) return;

        button.addEventListener('click', function () {
            // Совместимость с HTTP
            const tempInput = document.createElement('input');
            tempInput.value = url;
            document.body.appendChild(tempInput);
            tempInput.select();

            try {
                const success = document.execCommand('copy');
                if (success && message) {
                    message.style.display = 'inline';
                    setTimeout(() => {
                        message.style.display = 'none';
                    }, 2000);
                }
            } catch (err) {
                alert('Ошибка при копировании ссылки: ' + err);
            }

            document.body.removeChild(tempInput);
        });
    });
}