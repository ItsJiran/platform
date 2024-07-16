<template>
    <v-sheet color="transparent" class="position-relative">
        <v-toolbar :color="theme">
            <v-btn icon="arrow_back" @click="openFormData"></v-btn>

            <v-toolbar-title
                class="text-body-2 font-weight-bold text-uppercase"
                >{{ page.name }}</v-toolbar-title
            >

            <v-spacer></v-spacer>

            <v-btn v-if="!hideUpdate" icon @click="postFormEdit">
                <v-icon>save</v-icon>

                <v-tooltip activator="parent" location="bottom"
                    >Simpan</v-tooltip
                >
            </v-btn>

            <v-btn
                v-if="withHelpdesk"
                :color="helpState ? 'white' : `${theme}-lighten-3`"
                icon
                @click="helpState = !helpState"
            >
                <v-icon
                    :style="
                        helpState
                            ? 'transform: rotate(180deg)'
                            : 'transform: rotate(0deg)'
                    "
                    >menu_open</v-icon
                >

                <v-tooltip activator="parent" location="bottom"
                    >Informasi</v-tooltip
                >
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
                        edit
                    </div>
                </div>
            </div>

            <v-sheet
                :style="`max-width: ${maxWidth}`"
                class="mt-9 pt-9 mx-auto"
                min-height="calc(100vh - 175px)"
                rounded="lg"
            >
                <slot :record="record" :theme="theme"></slot>
            </v-sheet>
        </v-sheet>
    </v-sheet>

    <!-- <v-card
        class="overflow-hidden"
        :color="`${theme}-lighten-5`"
        height="calc(100vh - 72px)"
        rounded="lg"
    >
        <v-card-text class="mx-auto" style="width: 500px">
            <v-card rounded="lg">
                <v-card-text>
                    <slot :record="record"></slot>
                </v-card-text>
            </v-card>
        </v-card-text>
    </v-card> -->

    <form-help mode="edit" :withActivityLogs="withActivityLogs">
        <template v-slot:forminfo>
            <slot name="forminfo" :theme="theme"></slot>
        </template>

        <template v-slot:helpdesk>
            <slot name="helpdesk" :theme="theme"></slot>
        </template>
    </form-help>
</template>

<script>
import { usePageStore } from "@pinia/pageStore";
import { storeToRefs } from "pinia";

export default {
    name: "form-edit",

    props: {
        beforePost: Function,

        dataFromStore: Boolean,

        hideUpdate: Boolean,

        maxWidth: {
            type: String,
            default: "560px",
        },

        withHelpdesk: Boolean,

        withActivityLogs: Boolean,
    },

    setup(props) {
        const store = usePageStore();

        store.beforePost = props.beforePost;
        store.activityLog = props.withActivityLogs;

        const { helpState, highlight, key, page, pageKey, record, theme } =
            storeToRefs(store);

        const { getPageData, openFormData, postFormEdit } = store;

        return {
            helpState,
            highlight,
            key,
            page,
            pageKey,
            record,
            theme,

            getPageData,
            openFormData,
            postFormEdit,
        };
    },

    created() {
        this.getPageData(this.dataFromStore);
    },
};
</script>
