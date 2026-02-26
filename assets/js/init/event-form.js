import { initImageUpload } from './init-image-upload';

export function initEventForm() {
    initImageUpload({
        deleteUrlPrefix: '/cpanel/editor/events/images/',
        uploadUrl: '/cpanel/editor/events/dropzone',
    });
}