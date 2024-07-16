<template>
    <div class="v-list-wrap position-relative overflow-hidden">
        <v-list-item
            class="bg-white"
            :ripple="false"
            :value="value"
            style="z-index: 1"
        >
            <template v-slot:prepend>
                <v-avatar :color="`${theme}-lighten-4`">
                    <v-icon class="with-shadow" color="white">{{
                        icon ?? page.icon
                    }}</v-icon>
                </v-avatar>
            </template>

            <template v-slot:title>
                <slot name="title">{{ value[title] }}</slot>
            </template>

            <template v-slot:subtitle>
                <slot name="subtitle">{{ value[subtitle] }}</slot>
            </template>
        </v-list-item>

        <div
            :class="`bg-${theme}-lighten-5`"
            class="d-flex position-absolute h-100 w-100 px-4 py-3"
            style="top: 0; z-index: 0"
        >
            <v-spacer></v-spacer>

            <v-btn
                :color="`${theme}`"
                elevation="4"
                size="small"
                variant="flat"
                icon
                @click="openFormShow"
            >
                <v-icon class="with-shadow" color="white">folder</v-icon>
            </v-btn>

            <v-btn
                v-if="showDelete"
                :color="`${highlight}-darken-2`"
                class="ml-4"
                elevation="4"
                size="small"
                variant="flat"
                icon
            >
                <v-icon class="with-shadow" color="white">delete</v-icon>
            </v-btn>
        </div>
    </div>
    <v-divider></v-divider>
</template>

<script>
import { usePageStore } from "@pinia/pageStore";
import { storeToRefs } from "pinia";

export default {
    name: "item-data",

    props: {
        icon: {
            type: String,
            default: null,
        },

        showDelete: {
            type: Boolean,
            default: false,
        },

        subtitle: {
            type: String,
            default: "updated_at",
        },

        title: {
            type: String,
            default: "name",
        },

        value: {
            type: Object,
            default: () => ({}),
        },
    },

    setup() {
        const store = usePageStore();

        const { highlight, page, theme } = storeToRefs(store);

        const { openFormShow } = store;

        return {
            highlight,
            page,
            theme,

            openFormShow,
        };
    },
};
</script>
