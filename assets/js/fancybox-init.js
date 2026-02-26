import "@fancyapps/ui/dist/fancybox/fancybox.css";
import { Fancybox } from "@fancyapps/ui";

Fancybox.bind('[data-fancybox="gallery"]', {
    Toolbar: false, // Убирает верхнюю панель
    closeButton: "top", // Кнопка закрытия сверху
    dragToClose: true, // Закрытие свайпом вниз
    Image: {
        zoom: false, // Отключает увеличение
    },
    Thumbs: false, // Отключает миниатюры
});