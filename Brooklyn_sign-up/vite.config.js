import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';

export default defineConfig({
    server: {
        https: {
            key: fs.readFileSync('C:/Users/julia/.config/herd/config/valet/Certificates/brooklyn_sign-up.test.key'),
            cert: fs.readFileSync('C:/Users/julia/.config/herd/config/valet/Certificates/brooklyn_sign-up.test.crt'),
        },
        host: 'brooklyn_sign-up.test',
        port: 5173,
        hmr: {
            host: 'brooklyn_sign-up.test',
            protocol: 'wss',
        },
    },

    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
