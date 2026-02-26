export function initBottomAddMenuToggle() {
    const toggleButton = document.getElementById('addMenuToggle');
    const menuWrapper = document.getElementById('addMenu');

    if (!toggleButton || !menuWrapper) return;

    let isMenuOpen = false;

    // Снимаем старые обработчики (в случае hot reload или turbo)
    const newToggleButton = toggleButton.cloneNode(true);
    toggleButton.parentNode.replaceChild(newToggleButton, toggleButton);

    newToggleButton.addEventListener('click', function (e) {
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
        const clickedInside = menuWrapper.contains(e.target) || newToggleButton.contains(e.target);

        if (!clickedInside && isMenuOpen) {
            menuWrapper.classList.remove('active');
            setTimeout(() => {
                menuWrapper.classList.add('d-none');
                isMenuOpen = false;
            }, 200);
        }
    });
}