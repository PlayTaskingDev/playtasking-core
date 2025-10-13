import './bootstrap';
import './modal';
import './datepicker';
import './custom';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import.meta.glob([
    '../images/**',
    '../fonts/**',
]);