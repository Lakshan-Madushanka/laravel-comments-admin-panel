import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            hotFile: 'public/vendor/lakm/comments-admin-panel/comments-admin-panel.hot',
            buildDirectory: 'vendor/lakm/comments-admin-panel/build',
            input: ['resources/js/app.js'],
            refresh: true,
        }),
    ],
});
