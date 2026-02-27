import './bootstrap';
import intersect from '@alpinejs/intersect';

document.addEventListener('alpine:init', () => {
    Alpine.plugin(intersect);
});
