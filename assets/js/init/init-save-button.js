export function initSaveButtonCountdown() {
    document.addEventListener('submit', function (e) {
        const form = e.target;

        if (!form.matches('form[data-form-type="ai-generate"]')) return;

        const button = form.querySelector('#saveButton');
        if (!button) return;

        button.disabled = true;

        let counter = 20;

        const render = () => {
            button.innerHTML = `
                <span class="spinner-border spinner-border-sm mr-1"
                      role="status"
                      aria-hidden="true"></span>
                Генерация... (${counter})
            `;
        };

        render();

        const intervalId = setInterval(() => {
            counter--;
            render();

            if (counter <= 0) {
                clearInterval(intervalId);
            }
        }, 1000);
    });
}