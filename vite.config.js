import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer'; // Import it directly like this

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        https: process.env.NODE_ENV === 'production',
    },
    css: {
        postcss: {
            plugins: [
                tailwindcss(),
                autoprefixer(), // Use the imported variable
            ],
        },
    },
});
