class CrudForm {
    constructor(selector) {
        this.form = document.querySelector(selector);
        this.fields = this.form.querySelectorAll('[data-field]');
    }

    fill(data) {
        console.log(data);
        this.fields.forEach(el => {
            const path = el.dataset.field.split('.');
            console.log(path)
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