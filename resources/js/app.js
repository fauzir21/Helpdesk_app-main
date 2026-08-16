import './bootstrap';

import Alpine from 'alpinejs';
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle.js';
import Chart from 'chart.js/dist/Chart.js';
import { DataTable } from 'simple-datatables';
import Litepicker from 'litepicker';
import feather from 'feather-icons';
import '@fortawesome/fontawesome-free/js/all.js';

window.Alpine = Alpine;
window.bootstrap = bootstrap;
window.Chart = Chart;
window.simpleDatatables = { DataTable };
window.Litepicker = Litepicker;
window.feather = feather;

Alpine.start();

// Pastikan icon dirender
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
});
