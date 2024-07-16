// Plugins
import vuetify from "./vuetify";
import pinia from "./pinia";
import router from "./router";
import { Storage } from "./storage";
import { RequestInstance } from "./request";
import { useRouter, useRoute } from "vue-router";

export function registerDesktopPlugins(app) {
    const components = import.meta.glob("@components/desktop/**/index.vue", {
        eager: true,
    });

    Object.entries(components).forEach((file) => {
        app.component(file[1].default.name, file[1].default);
    });

    app.config.globalProperties.$storage = new Storage();
    app.config.globalProperties.$http = RequestInstance;

    pinia.use(({ store }) => {
        store.$http = app.config.globalProperties.$http;
        store.$storage = app.config.globalProperties.$storage;
        store.$route = useRoute();
        store.$router = useRouter();
    });

    app.use(vuetify).use(pinia).use(router);
}

export function registerMobilePlugins(app) {
    const components = import.meta.glob("@components/mobile/**/index.vue", {
        eager: true,
    });

    Object.entries(components).forEach((file) => {
        app.component(file[1].default.name, file[1].default);
    });

    app.config.globalProperties.$storage = new Storage();
    app.config.globalProperties.$http = RequestInstance;

    pinia.use(({ store }) => {
        store.$http = app.config.globalProperties.$http;
        store.$storage = app.config.globalProperties.$storage;
        store.$route = useRoute();
        store.$router = useRouter();
    });

    app.use(vuetify).use(pinia).use(router);
}
