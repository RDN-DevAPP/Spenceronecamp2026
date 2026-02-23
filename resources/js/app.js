import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue');
        const page = pages[`./Pages/${name}.vue`];
        if (!page) throw new Error(`Page not found: ${name}`);
        return page();
    },
    setup({ el, App, props, plugin }) {
        createApp({
            render() {
                return h(
                    'Transition',
                    { name: 'page', mode: 'out-in' },
                    { default: () => h(App, props) }
                );
            },
        })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#4B2E2A',
        showSpinner: true,
    },
});