<template>
    <v-sheet color="transparent" class="position-relative">
        <v-toolbar :color="theme">
            <v-app-bar-nav-icon
                @click="railMode = !railMode"
            ></v-app-bar-nav-icon>

            <v-toolbar-title
                class="font-weight-bold text-body-2 text-uppercase"
                >{{ module.name }}</v-toolbar-title
            >

            <v-spacer></v-spacer>

            <v-btn icon @click="gotoAccountDashboard">
                <v-icon>exit_to_app</v-icon>
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
            class="position-relative bg-transparent overflow-x-hidden overflow-y-auto scrollbar-none px-4"
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
                                :color="`${highlight}-lighten-2`"
                                size="64"
                                style="font-size: 22px"
                            >
                                <v-icon :color="`${theme}-darken-1`">{{
                                    page.icon
                                }}</v-icon>
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

            <v-card
                :style="`max-width: ${maxWidth}`"
                class="mt-9 pt-9 mx-auto"
                rounded="lg"
                flat
            >
                <div class="py-4">
                    <v-divider>
                        <v-chip :color="`${theme}`" variant="flat">
                            <span class="text-caption text-uppercase"
                                >menu</span
                            >
                        </v-chip>
                    </v-divider>
                </div>

                <v-sheet class="mx-auto bg-transparent pa-4" max-width="720">
                    <v-row justify="center" align="center" dense>
                        <v-col
                            v-for="(page, index) in dockMenus"
                            class="d-flex justify-center"
                            cols="2"
                            :key="index"
                        >
                            <v-card
                                :border="`border-${theme} sm`"
                                :color="`${theme}-lighten-5`"
                                class="text-center"
                                rounded="lg"
                                width="90"
                                flat
                                @click="openPage(page)"
                            >
                                <v-card-text class="px-2 pt-3 pb-2">
                                    <v-avatar
                                        :color="theme"
                                        size="44"
                                        style="font-size: 18px"
                                    >
                                        <v-icon
                                            :color="highlight"
                                            :icon="page.icon"
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
                                        {{ page.name }}
                                    </div>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-sheet>

                <div class="py-2">
                    <v-divider>
                        <v-chip :color="`${theme}`" variant="text">
                            <v-icon>more_horiz</v-icon>
                        </v-chip>
                    </v-divider>
                </div>

                <slot></slot>
            </v-card>
        </v-sheet>
    </v-sheet>
</template>

<script>
import { usePageStore } from "@pinia/pageStore";
import { storeToRefs } from "pinia";

export default {
    name: "page-home",

    emits: {
        initialized: null,
    },

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

        const { auth, dockMenus, highlight, module, page, railMode, theme } =
            storeToRefs(store);

        const { getDashboard } = store;

        return {
            getDashboard,

            auth,
            dockMenus,
            highlight,
            module,
            page,
            railMode,
            theme,
        };
    },

    mounted() {
        this.getDashboard((response) => {
            this.$emit("initialized", { record: response });
        });
    },

    methods: {
        gotoAccountDashboard: function () {
            this.$router.push({ name: "account-dashboard" });
        },

        openPage: function (page) {
            this.$router.push({ name: page.slug });
        },
    },
};
</script>
