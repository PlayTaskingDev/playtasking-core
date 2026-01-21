import './bootstrap';
import './modal';
import './datepicker';
import './custom';
import './crud-form';

import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';

// flatpickr
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';

window.Alpine = Alpine;
window.ApexCharts = ApexCharts;
window.flatpickr = flatpickr;
Alpine.start();

import.meta.glob([
    '../images/**',
    '../fonts/**',
]);

// Initialize components on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    // Map imports
    if (document.querySelector('#mapOne')) {
        import('./components/map').then(module => module.initMap());
    }

    // Chart imports
    if (document.querySelector('#chartOne')) {
        import('./components/chart/chart-1').then(module => module.initChartOne());
    }
    if (document.querySelector('#chartTwo')) {
        import('./components/chart/chart-2').then(module => module.initChartTwo());
    }
    if (document.querySelector('#chartThree')) {
        import('./components/chart/chart-3').then(module => module.initChartThree());
    }
    if (document.querySelector('#chartSix')) {
        import('./components/chart/chart-6').then(module => module.initChartSix());
    }
    if (document.querySelector('#chartEight')) {
        import('./components/chart/chart-8').then(module => module.initChartEight());
    }
    if (document.querySelector('#chartThirteen')) {
        import('./components/chart/chart-13').then(module => module.initChartThirteen());
    }

});

class CrudForm {
    constructor(selector) {
        this.form = document.querySelector(selector);
        this.fields = this.form.querySelectorAll('[data-field]');
    }

    fill(data) {
        this.fields.forEach(el => {
            //TODO funcion para rellenar formulario de manera automatica basado en respuesta
            
            const path = el.dataset.field.split('.');
            let value = data;
            path.forEach(p => value = value?.[p]);

            if (el.type === 'checkbox') el.checked = !!value;
            else if (el.tagName === 'IMG') el.src = value;
            else el.value = value ?? '';
        });
    }

    clear() {
        this.fields.forEach(el => {
            if (el.type === 'checkbox') el.checked = false;
            else el.value = '';
        });
    }

    setAction(url) {
        this.form.action = url;
    }
}
class CampaignModal {
    constructor() {
        this.modalEl = document.getElementById('campaign-modal');
        this.modal = new Modal(this.modalEl);
        this.form = new CrudForm('#form-campaign');

        this.registerEvents();
    }

    registerEvents() {
        document.querySelectorAll('[data-action]').forEach(btn => {
            btn.addEventListener('click', e => this.open(e.currentTarget));
        });
    }

    async open(button) {
        const action = button.dataset.action;
        const saveRoute = button.dataset.saveRoute;

        this.form.setAction(saveRoute);

        if (action === 'create') {
            this.form.clear();
            this.setMethod('POST');
            this.setTitle('Nueva Campaña');
        }

        if (action === 'edit') {
            this.setMethod('PATCH');
            this.setTitle('Editar Campaña');

            const response = await axios.get(button.dataset.editRoute);
            this.form.fill(response.data.data);
        }

        this.modal.show();
    }

    setMethod(method) {
        document.getElementById('method-field').value = method;
    }

    setTitle(text) {
        document.getElementById('campaign-modal-title').innerText = text;
    }
}

new CampaignModal();