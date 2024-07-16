<template>
    <v-layout :class="`bg-${theme}-darken-2`">
        <v-system-bar color="transparent" height="4"></v-system-bar>

        <v-navigation-drawer
            color="transparent"
            style="height: calc(100vh - 8px); padding-left: 4px"
            rail-width="60"
            disable-resize-watcher
            permanent
            :rail="railMode"
        >
            <template v-slot:prepend>
                <v-toolbar :color="theme" rounded="t-lg">
                    <v-avatar class="ml-2 mr-1" :color="`${theme}-lighten-3`">
                        <v-img
                            :src="
                                auth.avatar ??
                                `/avatars/${auth.gender}-avatar.svg`
                            "
                        ></v-img>
                    </v-avatar>

                    <v-toolbar-title class="text-white" v-if="auth">
                        <div class="text-body-2">
                            {{ auth.username }}
                        </div>
                        <div class="text-caption" style="line-height: 1">
                            <small>{{ auth.usermail }}</small>
                        </div>
                    </v-toolbar-title>
                </v-toolbar>
            </template>

            <template v-slot:default>
                <div
                    :class="`bg-${theme}`"
                    class="position-relative h-100 rounded-b-lg overflow-hidden"
                >
                    <v-list
                        :active-class="`bg-${theme}-lighten-5`"
                        :class="`bg-${theme}-lighten-5`"
                        class="h-100 rounded-t-lg"
                        nav
                    >
                        <v-list-item
                            v-for="(item, index) in sideMenus"
                            :key="index"
                            :to="{ name: item.slug }"
                        >
                            <template v-slot:prepend="{ isActive }">
                                <v-icon
                                    :color="
                                        isActive
                                            ? `${theme}-darken-2`
                                            : `${theme}-darken-1`
                                    "
                                    :icon="item.icon"
                                ></v-icon>
                            </template>

                            <template v-slot:default="{ isActive }">
                                <v-list-item-title
                                    :class="
                                        isActive
                                            ? `text-${theme}-darken-2`
                                            : `text-${theme}-darken-1`
                                    "
                                    >{{ item.name }}</v-list-item-title
                                >
                            </template>
                        </v-list-item>
                    </v-list>
                </div>
            </template>
        </v-navigation-drawer>

        <v-main
            :style="
                railMode ? `--v-layout-left: 64px` : `--v-layout-left: 260px`
            "
        >
            <div
                :class="`bg-transparent`"
                class="rounded-lg overflow-hidden"
                style="height: calc(100vh - 8px); width: calc(100% - 4px)"
            >
                <router-view key="pagebase" />
            </div>
        </v-main>

        <v-overlay
            :model-value="overlay"
            class="align-center justify-center"
            scroll-strategy="block"
            persistent
        >
            <v-progress-circular
                :color="theme"
                :width="3"
                size="64"
                indeterminate
            >
                <template v-slot:default><small>loading</small></template>
            </v-progress-circular>
        </v-overlay>

        <v-snackbar v-model="snackbar.state" multi-line>
            {{ snackbar.text }}

            <template v-slot:actions>
                <v-btn
                    :color="snackbar.color"
                    variant="text"
                    @click="snackbar.state = false"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </v-layout>
</template>

<script>
import { usePageStore } from "@pinia/pageStore";
import { storeToRefs } from "pinia";

export default {
    name: "page-base",

    props: {
        moduleName: {
            type: String,
            default: null,
        },

        moduleKey: {
            type: String,
            default: null,
        },

        moduleType: {
            type: String,
            default: null,
        },
    },

    setup(props) {
        const store = usePageStore();

        store.moduleName = props.moduleName;
        store.moduleKey = props.moduleKey;
        store.moduleType = props.moduleType;

        const {
            auth,
            dockMenus,
            overlay,
            railMode,
            sideMenus,
            snackbar,
            theme,
        } = storeToRefs(store);

        const { initModule } = store;

        return {
            auth,
            dockMenus,
            initModule,
            overlay,
            railMode,
            sideMenus,
            snackbar,
            theme,
        };
    },

    created() {
        this.initModule({ mobile: false });
    },
};
</script>
