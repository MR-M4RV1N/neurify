export function initProfileChat(profileId, chatCheckUrl, chatNewUrl) {
    const chatButton = document.getElementById('chatButton');
    const chatText = document.getElementById('chatText');
    const confirmModalEl = document.getElementById('confirmModal');
    const confirmButton = document.getElementById('confirmButton');

    if (!chatButton || !chatText || !confirmModalEl || !confirmButton) return;

    const confirmModal = new bootstrap.Modal(confirmModalEl);

    chatButton.addEventListener('click', () => {
        chatText.textContent = 'Loading...';

        fetch(chatCheckUrl)
            .then(response => response.json())
            .then(data => {
                if (data.result) {
                    window.location.href = chatNewUrl;
                } else {
                    confirmModal.show();
                    chatText.textContent = 'Send a message';
                }
            })
            .catch(error => {
                console.error('Chat check error:', error);
                chatText.textContent = 'Send a message';
            });
    });

    confirmButton.addEventListener('click', () => {
        window.location.href = chatNewUrl;
    });
}