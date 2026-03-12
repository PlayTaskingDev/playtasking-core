import { Modal } from 'flowbite';
import CrudForm from './crud-form'

export default class CrudModal {
  constructor(config) {
    this.modalEl = document.getElementById(config.modalId);
    this.modal = new Modal(this.modalEl);

    this.form = new CrudForm(`#${config.formId}`);

    this.titleNew = config.titleNew;
    this.titleEdit = config.titleEdit;

    this.registerEvents();
  }

  registerEvents() {
    document.querySelectorAll('[data-action]').forEach(btn => {
      btn.addEventListener('click', e => this.open(e.currentTarget));
    });
  }

  async open(button) {
    const action = button.dataset.action;
    const type = button.dataset.modalType;
    const saveRoute = button.dataset.saveRoute;

    this.form.setAction(saveRoute);

    if (action === 'create') {
      this.form.clear();
      this.setMethod('POST');
      this.setTitle(this.titleNew);
    }

    if (action === 'edit') {
      this.setMethod('PATCH');
      this.setTitle(this.titleEdit);

      const response = await axios.get(button.dataset.editRoute);
      if(type !== 'upload'){
        this.form.fill(response.data.data);
      }
    }

    this.modal.show();
  }

  setMethod(method) {
    document.getElementById('method-field').value = method;
  }

  setTitle(text) {
    document.getElementById('modal-title').innerText = text;
  }
}
