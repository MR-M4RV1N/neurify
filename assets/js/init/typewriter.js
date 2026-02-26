export function initTypewriter({ selector, text, speed = 100 }) {
    const outputEl = document.querySelector(selector);
    if (!outputEl || !text) return;

    let index = 0;

    function type() {
        if (index < text.length) {
            outputEl.innerHTML += text.charAt(index);
            index++;
            setTimeout(type, speed);
        }
    }

    // Очистим элемент на всякий случай
    outputEl.innerHTML = '';
    type();
}