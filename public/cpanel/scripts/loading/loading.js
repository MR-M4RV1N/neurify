document.addEventListener('DOMContentLoaded', function () {
    const loadingBar = document.getElementById('loading-bar');

    // Добавляем обработчик клика на все ссылки и кнопки
    document.querySelectorAll('a, button').forEach(element => {
        element.addEventListener('click', function (event) {
            // Показываем линию загрузки
            loadingBar.classList.add('active');

            // Для демонстрации убираем линию через 2 секунды
            // В реальной ситуации убирайте линию по завершении загрузки
            setTimeout(() => {
                loadingBar.classList.remove('active');
            }, 2000); // Время симуляции загрузки
        });
    });
});