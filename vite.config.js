import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import manifestSRI from 'vite-plugin-manifest-sri';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/js/app.js',
                'resources/js/glgc/gcgpzl.js',
                'resources/js/glgc/gpzl.js'
            ],
            refresh: true,
        }),
        manifestSRI(),
    ],
});
