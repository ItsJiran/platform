<template>
    <v-sheet color="transparent" class="position-relative">
        <v-toolbar :color="theme">
            <v-app-bar-nav-icon
                @click="railMode = !railMode"
            ></v-app-bar-nav-icon>

            <v-toolbar-title
                class="text-body-2 font-weight-bold text-uppercase"
                >{{ module.name }}</v-toolbar-title
            >

            <v-spacer></v-spacer>

            <slot name="toolbar"></slot>

            <v-btn
                v-if="withProperty"
                icon
                @click="sidenavState = !sidenavState"
            >
                <v-icon>{{ sidenavState ? "close" : "filter_list" }}</v-icon>
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
            class="position-relative bg-transparent overflow-x-hidden overflow-y-auto px-4 scrollbar-none"
            height="calc(100vh - 72px)"
            width="100%"
        >
            <div
                class="position-absolute text-center"
                style="width: calc(100% - 32px); z-index: 1"
            >
                <div
                    class="d-flex flex-column align-center justify-center position-relative mx-auto"
                    :style="`max-width: ${maxWidth}`"
                >
                    <v-card :color="`${theme}`" rounded="pill">
                        <v-card-text class="pa-1">
                            <v-avatar
                                :color="`${theme}-lighten-2`"
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
                        class="text-caption text-white position-absolute py-1 font-weight-bold text-uppercase"
                        style="top: 0; right: 0"
                    >
                        {{ page.name }}
                    </div>
                </div>
            </div>

            <v-card
                :style="`max-width: ${maxWidth}`"
                class="mt-9 pt-9 mx-auto position-relative"
                rounded="lg"
                flat
            >
                <slot :combos="combos" :record="record" :theme="theme"></slot>
            </v-card>

            <div class="text-caption py-1" style="line-height: 0">&nbsp;</div>
        </v-sheet>
    </v-sheet>

    <page-filter></page-filter>
</template>

<script>
import { usePageStore } from "@pinia/pageStore";
import { storeToRefs } from "pinia";

export default {
    name: "page-blank",

    props: {
        maxWidth: {
            type: String,
            default: "900px",
        },

        pagePath: {
            type: String,
            default: null,
        },

        pageName: {
            type: String,
            default: null,
        },

        pageKey: {
            type: String,
            default: null,
        },

        pageTitle: {
            type: String,
            default: null,
        },

        withProperty: {
            type: Boolean,
            default: false,
        },
    },

    setup(props) {
        const store = usePageStore();

        store.pageKey = props.pageKey;
        store.pageName = props.pageName;
        store.pagePath = props.pagePath;
        store.pageTitle = props.pageTitle;

        const {
            combos,
            highlight,
            module,
            page,
            record,
            sidenavState,
            railMode,
            theme,
        } = storeToRefs(store);

        const { getPageData } = store;

        return {
            combos,
            highlight,
            module,
            page,
            record,
            sidenavState,
            railMode,
            theme,

            getPageData,
        };
    },

    created() {
        this.getPageData();
    },
};
</script>
