import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            host: 'https://schedulifyadmin-production.up.railway.app',
            protocol: 'wss',
        },
    },
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
    }
});
