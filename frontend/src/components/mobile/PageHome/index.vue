<template>
    <v-toolbar :color="theme">
        <v-toolbar-title class="text-body-2 font-weight-bold text-uppercase">{{
            module.name
        }}</v-toolbar-title>

        <v-spacer></v-spacer>

        <v-btn icon @click="gotoAccountDashboard">
            <v-icon>exit_to_app</v-icon>
        </v-btn>
    </v-toolbar>

    <v-sheet
        :color="`${theme}`"
        class="mx-auto position-absolute w-100 rounded-b-xl"
        height="192"
    ></v-sheet>

    <v-responsive
        height="calc(100vh - (56px + 64px))"
        class="bg-transparent overflow-x-hidden overflow-y-auto px-4"
        content-class="position-relative"
    >
        <div
            class="position-absolute text-center"
            style="height: 32px; width: calc(100vw - 32px); z-index: 1"
        >
            <div
                class="d-flex flex-column align-center justify-center position-relative"
            >
                <v-sheet :color="`${theme}`" elevation="4" rounded="pill">
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
                </v-sheet>

                <div
                    :class="`text-${theme}-lighten-4`"
                    class="text-caption text-white position-absolute py-2 font-weight-bold text-uppercase"
                    style="font-size: 0.7rem !important; top: 0; right: 0"
                >
                    {{ page.name }}
                </div>
            </div>
        </div>

        <v-sheet
            class="mt-9 pt-9"
            elevation="1"
            min-height="calc(100% - 52px)"
            rounded="lg"
        >
            <v-card-text>
                <v-row dense>
                    <v-col
                        cols="3"
                        v-for="(page, index) in dockMenus"
                        :key="index"
                    >
                        <v-card
                            :border="`border-${theme} thin`"
                            :color="`${theme}-lighten-5`"
                            class="text-center"
                            rounded="md"
                            width="100%"
                            flat
                            @click="openPage(page)"
                        >
                            <v-card-text class="pa-3 pb-2">
                                <v-avatar
                                    :color="theme"
                                    size="36"
                                    style="font-size: 11px"
                                >
                                    <v-icon
                                        :color="highlight"
                                        :icon="page.icon"
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
                                    {{ page.name }}
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-card-text>

            <v-divider>
                <v-chip
                    :color="theme"
                    density="comfortable"
                    size="small"
                    variant="flat"
                >
                    <v-icon>more_horiz</v-icon>
                </v-chip>
            </v-divider>

            <slot :record="record" :pulse="pulse" :theme="theme"></slot>
        </v-sheet>

        <div class="py-2"></div>
    </v-responsive>
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

        const {
            auth,
            dockMenus,
            highlight,
            module,
            navigationState,
            page,
            pulse,
            railMode,
            record,
            theme,
        } = storeToRefs(store);

        const { getDashboard } = store;

        return {
            getDashboard,

            auth,
            highlight,
            module,
            navigationState,
            page,
            pulse,
            railMode,
            record,
            dockMenus,
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
