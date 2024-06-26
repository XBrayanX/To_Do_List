import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/home.scss',
                'resources/js/app.js',
                'resources/js/home.js'
            ],
            refresh: true,
        }),
    ],
});
