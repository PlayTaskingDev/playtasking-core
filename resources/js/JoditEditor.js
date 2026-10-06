import { Jodit } from 'jodit';
import 'jodit/es2021/jodit.min.css';
import 'jodit/esm/plugins/source/source.js';

export default class JoditEditor {

    static initAll() {

        document
            .querySelectorAll('.jodit-component')
            .forEach(element => {

                if (element.dataset.joditInitialized) {
                    return;
                }

                element.dataset.joditInitialized = 'true';

                Jodit.make(element, {

                    height: 350,

                    toolbarAdaptive: false,

                    buttons: [
                        'source',
                        '|',
                        'bold',
                        'italic',
                        'underline',
                        'strikethrough',
                        '|',
                        'ul',
                        'ol',
                        '|',
                        'font',
                        'fontsize',
                        'paragraph',
                        '|',
                        'image',
                        'link',
                        'table',
                        '|',
                        'align',
                        '|',
                        'undo',
                        'redo',
                        '|',
                        'fullsize'
                    ]

                });

            });

    }
}