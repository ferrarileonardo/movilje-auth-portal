import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { quasar } from '@quasar/vite-plugin';
import { fileURLToPath } from 'url';
import vue from '@vitejs/plugin-vue';
import { transformAssetUrls } from '@vitejs/plugin-vue';

export default defineConfig({
    build: {
        sourcemap: true, // ✅ Habilita mapas de fuente correctamente
      },
      server: {
        port: 5173, // ✅ Ajusta el puerto si es necesario
      },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: { transformAssetUrls }  // ✅ This will now be defined
        }),

        quasar({
            sassVariables: fileURLToPath(
                new URL('resources/css/quasar-variables.sass', import.meta.url)
            )
        })
    ],
});
