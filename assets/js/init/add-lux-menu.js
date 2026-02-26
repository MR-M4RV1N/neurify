export function initAddLuxMenu(toggleSelector = '#addMenuToggle', overlaySelector = '#addMenuOverlay') {
    const toggleButton = document.querySelector(toggleSelector);
    const overlay = document.querySelector(overlaySelector);
    const closeBtn = overlay?.querySelector('#closeAddMenu');

    if (!toggleButton || !overlay) {
        console.warn("Add menu elements not found");
        return;
    }

    const newToggleButton = toggleButton.cloneNode(true);
    toggleButton.parentNode.replaceChild(newToggleButton, toggleButton);

    let isMenuOpen = !overlay.classList.contains('hidden');

    newToggleButton.addEventListener('click', function (e) {
        e.preventDefault();

        if (!isMenuOpen) {
            overlay.classList.remove('hidden');
            isMenuOpen = true;
        } else {
            overlay.classList.add('hidden');
            isMenuOpen = false;
        }
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            overlay.classList.add('hidden');
            isMenuOpen = false;
        });
    }

    document.addEventListener('click', function (e) {
        const clickedInside = overlay.contains(e.target) || newToggleButton.contains(e.target);
        if (!clickedInside && isMenuOpen) {
            overlay.classList.add('hidden');
            isMenuOpen = false;
        }
    });
}