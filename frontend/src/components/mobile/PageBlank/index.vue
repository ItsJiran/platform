<template>
    <v-toolbar :color="theme">
        <v-btn icon>
            <v-icon>arrow_back</v-icon>
        </v-btn>

        <v-toolbar-title class="text-body-2 font-weight-bold text-uppercase">{{
            module.name
        }}</v-toolbar-title>

        <v-spacer></v-spacer>
    </v-toolbar>

    <v-sheet
        :color="`${theme}`"
        class="mx-auto position-absolute w-100 rounded-b-xl"
        height="192"
    ></v-sheet>

    <v-responsive
        :height="navigationState ? `calc(100vh - 120px)` : `calc(100vh - 64px)`"
        class="bg-transparent overflow-x-hidden overflow-y-auto px-4"
        content-class="position-relative"
    >
        <slot
            :combos="combos"
            :highlight="highlight"
            :record="record"
            :store="store"
            :theme="theme"
        ></slot>

        <div class="py-2"></div>
    </v-responsive>
</template>

<script>
import { usePageStore } from "@pinia/pageStore";
import { storeToRefs } from "pinia";

export default {
    name: "page-blank",

    props: {
        maxWidth: {
            type: String,
            default: "500px",
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

        const {
            combos,
            highlight,
            module,
            navigationState,
            page,
            sidenavState,
            railMode,
            record,
            theme,
        } = storeToRefs(store);

        const { getPageData } = store;

        return {
            combos,
            highlight,
            module,
            navigationState,
            page,
            sidenavState,
            railMode,
            record,
            theme,

            getPageData,
            store,
        };
    },

    created() {
        this.getPageData();
    },
};
</script>
