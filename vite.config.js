import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/vendor-dashboard.css',
                'resources/css/vendor-sidebar.css',
                'resources/css/vendor-layout.css',
                'resources/css/vendor-find-couples.css',
                'resources/css/vendor-messages.css',
                'resources/css/vendor-edit-profile.css',
                'resources/css/vendor-couple-profile.css',
                'resources/css/vendor-storefront.css',
                'resources/css/vendor-insights.css',
                'resources/css/vendor-current-clients.css',
                'resources/css/vendor-vendor-network.css',
                'resources/css/vendor-calendar.css',
                'resources/js/app.js',
                'resources/js/win-toast.js',
                'resources/js/message-page.js',
                'resources/js/chat.js',
                'resources/js/insights.js',
                'resources/js/vendor.js',
                'resources/js/client-utils.js',
                'resources/js/vendor-utils.js',
                'resources/js/vendor-sidebar-notifications.js',
                'resources/js/vendor-sidebar-mobile.js',
                'resources/js/vendor-register.js',
                'resources/js/couple-register.js',
                'resources/js/vendor-register-form.js',
                'resources/js/find-couples.js',
                'resources/js/vendor-messages.js',
                'resources/js/vendor-edit-profile.js',
                'resources/js/vendor-storefront.js',
                'resources/js/vendor-insights.js',
                'resources/js/vendor-refer-client.js',
                'resources/js/vendor-invite-vendor.js',
                'resources/js/chat-modal.js',
                'resources/js/vendor-calendar.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost',
        },
    },
    build: {
        minify: "terser",
        terserOptions: {
            toplevel: true
        }
    },
});
