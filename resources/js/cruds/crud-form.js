export default class CrudForm {
    constructor(selector) {
        this.form = document.querySelector(selector);
        this.fields = this.form.querySelectorAll('[data-field]');
    }

    fill(data) {
        this.fields.forEach(el => {
            const path = el.dataset.field.split('.');
            let value = data;
            path.forEach(p => value = value?.[p]);

            const isTxtVideo = el.dataset.isVideo || false;
            if (el.type === 'checkbox') {
                const type = el.dataset.checkboxType || 'boolean';

                if (type === 'boolean') {
                    el.checked = !!value;
                }

                if (type === 'relation') {
                    if (value) el.checked = value != null;
                    const pathRelation = el.dataset.relationFieldId.split('.');
                    let valueRelation = data
                    pathRelation.forEach(p=> valueRelation = valueRelation?.[p])
                    el.value = valueRelation;
                }

                return;
            }
            else if(isTxtVideo){
                el.value = value ?? '';
                const idIframe = 'video-'+path.at(-1)
                document.getElementById(idIframe).src = value ?? '';
            }
            else if (el.type === 'file') {
                const isImg = el.dataset.isImg || false;
                if(isImg){
                    el.value = value ?? '';
                    const idImg = 'img-'+path.at(-1)
                    document.getElementById(idImg).src = value ?? '/storage/dummy_assets/600x200.png';
                }
            }
            else if (el.tagName === 'TEXTAREA' && el.classList.contains('tinymce-component')) {
                const editor = tinymce.get(el.id);
                    if (editor) {
                        editor.setContent(value || '');
                    }
                    return;
                }

            else if(el.type === 'datetime-local') el.value = this.fromBackendToDatetimeLocal(value);
            else el.value = value ?? '';
        });
    }

    clear() {
        this.fields.forEach(el => {
            if (el.type === 'checkbox') el.checked = false;
            else if (el.tagName === 'TEXTAREA' && el.classList.contains('tinymce-component'))  tinymce.get(el.id).setContent('');
            else el.value = '';
        });
        const images = this.form.querySelectorAll("img");
        const iframes = this.form.querySelectorAll("iframe");
        images.forEach(img => {
            img.src = '/storage/dummy_assets/600x200.png';
        });
        iframes.forEach(img => {
            img.src = '';
        });
    }

    setAction(url) {
        this.form.action = url;
    }
    fromBackendToDatetimeLocal(value) {
        const date = new Date(value.replace(' ', 'T'));
        return this.formatDateTimeLocal(date);
    }
    formatDateTimeLocal(date = new Date()) {
        const pad = n => String(n).padStart(2, '0');
        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
    }
}