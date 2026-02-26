export function initAddMenuToggle() {
    // Это всё нужно на app_account_list
    const toggleButton = document.getElementById('addMenuToggle');
    const menuWrapper = document.getElementById('addMenu');

    if (!toggleButton || !menuWrapper) return;

    // Убираем старые обработчики, если вдруг они остались
    toggleButton.onclick = null;

    let isMenuOpen = false;

    toggleButton.addEventListener('click', function (e) {
        e.preventDefault();

        if (!isMenuOpen) {
            menuWrapper.classList.remove('d-none');
            setTimeout(() => {
                menuWrapper.classList.add('active');
                isMenuOpen = true;
            }, 10);
        } else {
            menuWrapper.classList.remove('active');
            setTimeout(() => {
                menuWrapper.classList.add('d-none');
                isMenuOpen = false;
            }, 200);
        }
    });

    document.addEventListener('click', function (e) {
        const clickedInside = menuWrapper.contains(e.target) || toggleButton.contains(e.target);

        if (!clickedInside && isMenuOpen) {
            menuWrapper.classList.remove('active');
            setTimeout(() => {
                menuWrapper.classList.add('d-none');
                isMenuOpen = false;
            }, 200);
        }
    });
}