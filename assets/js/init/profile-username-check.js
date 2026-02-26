export function initUsernameAvailabilityCheck() {
    const input = document.querySelector('[data-username-check-url]');
    if (!input) return;

    const checkUrl = input.dataset.usernameCheckUrl;
    const checkTarget = document.querySelector(input.dataset.usernameCheckTarget);
    const registerButton = document.getElementById(input.dataset.registerButtonId);
    const errorMessage = checkTarget?.dataset.errorMsg || 'Username already exists.';

    if (!checkTarget || !registerButton) return;

    input.addEventListener('input', function () {
        const username = input.value.trim();

        if (username.length === 0) {
            checkTarget.classList.add('d-none');
            registerButton.disabled = true;
            return;
        }

        fetch(`${checkUrl}?username=${encodeURIComponent(username)}`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    checkTarget.classList.remove('d-none');
                    checkTarget.textContent = errorMessage;
                    registerButton.disabled = true;
                } else {
                    checkTarget.classList.add('d-none');
                    checkTarget.textContent = '';
                    registerButton.disabled = false;
                }
            })
            .catch(error => {
                console.error('Username check failed:', error);
                registerButton.disabled = true;
            });
    });
}