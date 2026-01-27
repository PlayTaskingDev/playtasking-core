import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import manifestSRI from 'vite-plugin-manifest-sri';
import 'flowbite';
import { Modal } from 'flowbite';


export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/scss/app.scss',
                'resources/js/app.js',
                'resources/js/glgc/gcgpzl.js',
                'resources/js/glgc/gpzl.js',
                'resources/js/cruds/crud-form.js',
                'resources/js/cruds/crud-modal.js',
                'resources/js/cruds/campaign.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
        manifestSRI(),
    ],
});
