<template>
    <v-responsive class="h-100 w-100 overflow-x-hidden overflow-y-auto">
        <v-toolbar :color="`${theme}`">
            <v-toolbar-title class="text-body-2 font-weight-bold"
                >SiMASTEN</v-toolbar-title
            >

            <v-spacer></v-spacer>

            <v-btn icon>
                <v-icon class="with-shadow">power_settings_new</v-icon>

                <form-confirm icon="power_settings_new" title="keluar">
                    <v-card-text>
                        Saat Anda keluar dari aplikasi ini, semua data temporer
                        yang tersimpan pada perangkat ini juga akan di hapus.
                    </v-card-text>

                    <template v-slot:actions="{ isActive }">
                        <v-spacer></v-spacer>

                        <v-btn
                            :color="`${theme}-lighten-2`"
                            class="text-white mr-2"
                            variant="flat"
                            @click="isActive.value = false"
                            >batal</v-btn
                        >

                        <v-btn
                            :color="`${theme}`"
                            class="text-white"
                            variant="flat"
                            @click="signOut(() => (isActive.value = false))"
                            >keluar</v-btn
                        >
                    </template>
                </form-confirm>
            </v-btn>
        </v-toolbar>

        <v-sheet
            :color="`${theme}`"
            class="mx-auto position-absolute w-100 rounded-b-xl"
            height="192"
        ></v-sheet>

        <v-sheet
            :height="
                navigationState ? `calc(100vh - 120px)` : `calc(100vh - 64px)`
            "
            class="bg-transparent overflow-x-hidden overflow-y-auto px-4 pb-4 position-relative"
        >
            <v-sheet
                class="position-absolute"
                color="transparent"
                width="calc(100% - 32px)"
                style="z-index: 1"
            >
                <div class="d-flex justify-center">
                    <v-avatar
                        :color="`${theme}`"
                        class="pa-1 elevation-4"
                        rounded="pill"
                        size="72"
                    >
                        <v-avatar
                            :color="`${theme}-lighten-3`"
                            rounded="pill"
                            size="64"
                        >
                            <v-img
                                :src="
                                    auth.avatar ??
                                    `/avatars/${auth.gender}-avatar.svg`
                                "
                            ></v-img>
                        </v-avatar>
                    </v-avatar>
                </div>
            </v-sheet>

            <v-card class="mt-9 pt-9" elevation="1" rounded="lg" flat>
                <v-card-text class="text-center">
                    <div class="text-body-2 font-weight-medium text-uppercase">
                        {{ auth.username }}
                    </div>
                    <div class="text-caption">{{ auth.usermail }}</div>
                </v-card-text>

                <template
                    v-if="modules.personal && modules.personal.length > 0"
                >
                    <v-divider>
                        <v-chip
                            :color="theme"
                            density="comfortable"
                            size="small"
                            variant="flat"
                            >personal</v-chip
                        >
                    </v-divider>

                    <v-card-text>
                        <v-row justify="center" dense>
                            <v-col
                                cols="3"
                                v-for="(module, index) in modules.personal"
                                :key="index"
                            >
                                <v-card
                                    :border="`border-${theme} thin`"
                                    :color="`${theme}-lighten-5`"
                                    class="text-center"
                                    rounded="md"
                                    width="100%"
                                    @click="openModule(module)"
                                    flat
                                >
                                    <v-card-text class="pa-3 pb-2">
                                        <v-avatar
                                            :color="theme"
                                            style="font-size: 16px"
                                        >
                                            <v-icon
                                                :color="highlight"
                                                :icon="module.icon"
                                                size="large"
                                            ></v-icon>
                                        </v-avatar>
                                    </v-card-text>

                                    <v-card-text
                                        :class="`text-${theme}-darken-1`"
                                        class="pa-1 pb-2"
                                        style="max-height: 38px"
                                    >
                                        <div
                                            class="text-caption d-flex align-center justify-center overflow-hidden w-100 h-100"
                                            style="
                                                font-size: 72% !important;
                                                line-height: 1.25em;
                                            "
                                        >
                                            {{ module.name }}
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </template>

                <template
                    v-if="
                        modules.administrator &&
                        modules.administrator.length > 0
                    "
                >
                    <v-divider>
                        <v-chip
                            :color="theme"
                            density="comfortable"
                            size="small"
                            variant="flat"
                            >administrator</v-chip
                        >
                    </v-divider>

                    <v-card-text>
                        <v-row justify="center" dense>
                            <v-col
                                cols="3"
                                v-for="(module, index) in modules.administrator"
                                :key="index"
                            >
                                <v-card
                                    :border="`border-${theme} thin`"
                                    :color="`${theme}-lighten-5`"
                                    class="text-center"
                                    rounded="md"
                                    width="100%"
                                    @click="openModule(module)"
                                    flat
                                >
                                    <v-card-text class="pa-3 pb-2">
                                        <v-avatar
                                            :color="theme"
                                            style="font-size: 16px"
                                        >
                                            <v-icon
                                                :color="highlight"
                                                :icon="module.icon"
                                                size="large"
                                            ></v-icon>
                                        </v-avatar>
                                    </v-card-text>

                                    <v-card-text
                                        :class="`text-${theme}-darken-1`"
                                        class="pa-1 pb-2"
                                        style="max-height: 38px"
                                    >
                                        <div
                                            class="text-caption d-flex align-center justify-center overflow-hidden w-100 h-100"
                                            style="
                                                font-size: 72% !important;
                                                line-height: 1.25em;
                                            "
                                        >
                                            {{ module.name }}
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </template>
            </v-card>
            <slot></slot>
        </v-sheet>
    </v-responsive>
</template>

<script>
import { usePageStore } from "@pinia/pageStore";
import { storeToRefs } from "pinia";

export default {
    name: "user-home",

    props: {
        pageName: {
            type: String,
            default: null,
        },

        pageKey: {
            type: String,
            default: null,
        },
    },

    setup(props) {
        const store = usePageStore();

        store.pageName = props.pageName;
        store.pageKey = props.pageKey;

        const { auth, highlight, modules, navigationState, railMode, theme } =
            storeToRefs(store);

        const { getUserModules, signOut } = store;

        return {
            auth,
            highlight,
            modules,
            navigationState,
            railMode,
            theme,

            getUserModules,
            signOut,
        };
    },

    created() {
        this.getUserModules();
    },

    methods: {
        openModule: function (module) {
            this.$router.push({ name: module.slug + "-dashboard" });
        },
    },
};
</script>
