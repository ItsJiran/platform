<template>
    <v-sheet color="transparent" class="position-relative">
        <v-toolbar :color="theme">
            <v-app-bar-nav-icon
                @click="railMode = !railMode"
            ></v-app-bar-nav-icon>

            <v-toolbar-title class="text-body-2 font-weight-bold"
                >SiMASTEN</v-toolbar-title
            >

            <v-spacer></v-spacer>

            <v-btn icon>
                <v-icon>power_settings_new</v-icon>

                <form-confirm icon="power_settings_new" title="keluar aplikasi">
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
            :color="`${theme}-lighten-4`"
            class="mx-auto position-absolute w-100"
            height="100%"
        ></v-sheet>

        <v-sheet
            :color="`${theme}`"
            class="mx-auto position-absolute w-100 rounded-b-xl"
            height="192"
        ></v-sheet>

        <v-sheet
            class="position-relative bg-transparent overflow-x-hidden overflow-y-auto px-4"
            height="calc(100vh - 72px)"
            width="100%"
        >
            <div
                class="position-absolute text-center"
                style="width: calc(100% - 32px); z-index: 1"
            >
                <div
                    :style="`max-width: ${maxWidth}`"
                    class="d-flex flex-column align-center justify-center position-relative mx-auto"
                >
                    <v-card :color="`${theme}`" rounded="pill">
                        <v-card-text class="pa-1">
                            <v-avatar
                                :color="`${theme}-lighten-3`"
                                size="64"
                                style="font-size: 22px"
                            >
                                <v-img
                                    :src="
                                        auth.avatar ??
                                        `/avatars/${auth.gender}-avatar.svg`
                                    "
                                ></v-img>
                            </v-avatar>
                        </v-card-text>
                    </v-card>

                    <div
                        :class="`text-${theme}-lighten-4`"
                        class="text-caption text-white position-absolute pt-3 font-weight-bold text-uppercase"
                        style="top: 0; right: 0"
                    >
                        beranda
                    </div>
                </div>
            </div>

            <v-sheet
                :style="`max-width: ${maxWidth}`"
                class="mt-9 pt-9 mx-auto"
                rounded="lg"
            >
                <v-card-text class="text-center py-3">
                    <div
                        class="text-subtitle-1 text-uppercase font-weight-bold"
                    >
                        {{ auth.username }}
                    </div>
                    <div class="text-caption">{{ auth.usermail }}</div>
                </v-card-text>

                <template
                    v-if="modules.personal && modules.personal.length > 0"
                >
                    <v-divider>
                        <v-chip :color="`${theme}`" variant="flat">
                            <span class="text-caption text-uppercase"
                                >personal</span
                            >
                        </v-chip>
                    </v-divider>

                    <v-sheet
                        class="mx-auto bg-transparent pa-4"
                        max-width="720"
                    >
                        <v-row justify="center">
                            <v-col
                                cols="2"
                                v-for="(module, index) in modules.personal"
                                :key="index"
                            >
                                <v-card
                                    :border="`border-${theme} sm`"
                                    :color="`${theme}-lighten-5`"
                                    class="text-center"
                                    rounded="lg"
                                    width="100%"
                                    @click="openModule(module)"
                                    flat
                                >
                                    <v-card-text class="px-2 pt-3 pb-1">
                                        <v-avatar
                                            :color="theme"
                                            size="56"
                                            style="font-size: 20px"
                                        >
                                            <v-icon
                                                :color="highlight"
                                                :icon="module.icon"
                                            ></v-icon>
                                        </v-avatar>
                                    </v-card-text>

                                    <v-card-text
                                        :class="`text-${theme}-darken-1`"
                                        class="pt-1 px-2 pb-2"
                                        style="max-height: 42px"
                                    >
                                        <div
                                            class="text-caption d-flex align-center justify-center overflow-hidden w-100"
                                            style="
                                                line-height: 1.25em;
                                                height: 30px;
                                            "
                                        >
                                            {{ module.name }}
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-sheet>
                </template>

                <template
                    v-if="
                        modules.administrator &&
                        modules.administrator.length > 0
                    "
                >
                    <div class="py-3">
                        <v-divider>
                            <v-chip :color="`${theme}`" variant="flat">
                                <span class="text-caption text-uppercase"
                                    >administrator</span
                                >
                            </v-chip>
                        </v-divider>
                    </div>

                    <v-sheet
                        class="mx-auto bg-transparent pa-4"
                        max-width="720"
                    >
                        <v-row justify="center">
                            <v-col
                                cols="2"
                                v-for="(module, index) in modules.administrator"
                                :key="index"
                            >
                                <v-card
                                    :border="`border-${theme} sm`"
                                    :color="`${theme}-lighten-5`"
                                    class="text-center"
                                    rounded="lg"
                                    width="100%"
                                    @click="openModule(module)"
                                    flat
                                >
                                    <v-card-text class="px-2 pt-3 pb-1">
                                        <v-avatar
                                            :color="theme"
                                            size="56"
                                            style="font-size: 20px"
                                        >
                                            <v-icon
                                                :color="highlight"
                                                :icon="module.icon"
                                            ></v-icon>
                                        </v-avatar>
                                    </v-card-text>

                                    <v-card-text
                                        :class="`text-${theme}-darken-1`"
                                        class="pt-1 px-2 pb-2"
                                        style="max-height: 42px"
                                    >
                                        <div
                                            class="text-caption d-flex align-center justify-center overflow-hidden w-100"
                                            style="
                                                line-height: 1.25em;
                                                height: 30px;
                                            "
                                        >
                                            {{ module.name }}
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-sheet>
                </template>

                <div class="py-2">
                    <v-divider>
                        <v-chip :color="`${theme}`" variant="text">
                            <v-icon>more_horiz</v-icon>
                        </v-chip>
                    </v-divider>
                </div>

                <slot></slot>
            </v-sheet>
        </v-sheet>
    </v-sheet>
</template>

<script>
import { usePageStore } from "@pinia/pageStore";
import { storeToRefs } from "pinia";

export default {
    name: "user-home",

    props: {
        maxWidth: {
            type: String,
            default: "900px",
        },

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

        const { auth, highlight, modules, railMode, theme } =
            storeToRefs(store);

        const { getUserModules, signOut } = store;

        return {
            auth,
            highlight,
            modules,
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
