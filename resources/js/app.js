import './bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import './apexcharts.js';
import.meta.glob([
    '../images/**',
]);

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
