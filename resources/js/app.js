import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { Quasar } from 'quasar';

// Import icon libraries
import '@quasar/extras/material-icons/material-icons.css';

// Import Quasar CSS
import 'quasar/src/css/index.sass';

// ✅ Resuelve correctamente las páginas sin duplicaciones
createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`] || pages[`./Pages/Auth/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(Quasar, { plugins: {} }) // ✅ Integración de Quasar en una sola declaración
            .mount(el);
    },
});
