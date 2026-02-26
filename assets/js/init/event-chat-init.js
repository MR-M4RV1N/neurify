export function initChatModal() {
    const chatButton = document.getElementById('chatButton');
    const chatText = document.getElementById('chatText');
    const confirmModalEl = document.getElementById('confirmModal');
    const confirmButton = document.getElementById('confirmButton');

    if (!chatButton || !confirmButton || !confirmModalEl || !chatText) {
        console.warn('❌ Один из элементов чата не найден');
        return;
    }

    let confirmModal;
    try {
        confirmModal = new bootstrap.Modal(confirmModalEl, {});
    } catch (e) {
        console.error('Не удалось создать bootstrap.Modal:', e);
        return;
    }

    const checkUrl = chatButton.dataset.chatCheckUrl;
    const createUrl = chatButton.dataset.chatCreateUrl;

    chatButton.addEventListener('click', () => {
        chatText.textContent = 'Loading...';

        fetch(checkUrl)
            .then(response => response.json())
            .then(data => {
                if (data.result) {
                    window.location.href = createUrl;
                } else {
                    confirmModal.show();
                    chatText.textContent = 'Send a message';
                }
            })
            .catch(error => {
                console.error('Ошибка при проверке чата:', error);
                chatText.textContent = 'Send a message';
            });
    });

    confirmButton.addEventListener('click', () => {
        window.location.href = createUrl;
    });
}