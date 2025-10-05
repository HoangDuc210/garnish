import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    server: {
        proxy: {
            '/foo': 'http://localhost:4567',
        },
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/assets'),
            '@nm': path.resolve(__dirname, 'node_modules'),
        }
    },
    plugins: [
        laravel([
            'resources/assets/scss/vendor.scss',
            'resources/assets/scss/template.scss',
            'resources/assets/scss/custom.scss',
            'resources/assets/scss/pages/receipt.scss',
            'resources/assets/scss/pages/billing.scss',
            'resources/assets/scss/pages/deposit.scss',
            'resources/assets/vendor/datatables.css',
            'resources/assets/js/vendor.js',
            'resources/assets/js/pages/searchPost.js',
            'resources/assets/js/app.js',
            'resources/assets/js/pages/receipt.js',
            'resources/assets/js/pages/agent.js',
            'resources/assets/js/pages/deposit.js',
            'resources/assets/js/pages/billing.js',
            'resources/assets/js/pages/export.js',
            'resources/assets/js/pages/printer.js',
            'resources/assets/js/pages/unit.js',
            'resources/assets/js/pages/prev_next_page_url.js',
            'resources/assets/vendor/datatables.js',
            'resources/assets/js/pages/repeater_deposit.js',
            'resources/assets/js/pages/modal_search.js',
        ]),
    ],
});
