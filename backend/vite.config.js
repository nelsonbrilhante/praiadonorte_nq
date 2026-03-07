import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import os from 'os';

function getNetworkIP() {
    for (const interfaces of Object.values(os.networkInterfaces())) {
        for (const iface of interfaces) {
            if (iface.family === 'IPv4' && !iface.internal && !iface.address.startsWith('10.')) {
                return iface.address;
            }
        }
    }
    return 'localhost';
}

const host = getNetworkIP();

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament/admin.css',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        origin: `http://${host}:5173`,
        cors: true,
    },
});
