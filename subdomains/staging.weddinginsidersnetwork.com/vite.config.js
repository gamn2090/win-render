import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/message-page.js',
                'resources/js/chat.js',
                'resources/js/insights.js',
                'resources/js/vendor.js',
                'resources/js/client-utils.js',
                'resources/js/vendor-utils.js',
                'resources/js/vendor-register.js',
                'resources/js/couple-register.js',
                'resources/js/vendor-register-form.js',
                'resources/js/find-couples.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        minify: "terser",
        terserOptions: {
            toplevel: true
        }
    },	
});
