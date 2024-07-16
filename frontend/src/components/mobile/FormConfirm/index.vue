<template>
    <v-dialog activator="parent" max-width="400" persistent>
        <template v-slot:default="{ isActive }">
            <v-card height="283">
                <v-sheet
                    :color="`${theme}-lighten-4`"
                    class="mx-auto position-absolute w-100"
                    height="100%"
                ></v-sheet>

                <v-sheet
                    :color="`${theme}`"
                    class="mx-auto position-absolute w-100 rounded-b-xl"
                    height="162"
                ></v-sheet>

                <v-toolbar :color="theme" density="comfortable">
                    <v-toolbar-title
                        class="text-body-2 font-weight-bold text-uppercase"
                        >Konfirmasi</v-toolbar-title
                    >
                </v-toolbar>

                <v-sheet
                    class="position-relative bg-transparent overflow-hidden px-4"
                    height="226"
                    width="100%"
                >
                    <div
                        class="position-absolute text-center"
                        style="width: calc(100% - 32px); z-index: 1"
                    >
                        <div
                            class="d-flex flex-column align-center justify-center position-relative mx-auto"
                        >
                            <v-card :color="`${theme}`" rounded="pill">
                                <v-card-text class="pa-1">
                                    <v-avatar
                                        :color="`${highlight}-lighten-2`"
                                        size="56"
                                        style="font-size: 22px"
                                    >
                                        <v-icon :color="`${theme}-darken-1`">{{
                                            icon
                                        }}</v-icon>
                                    </v-avatar>
                                </v-card-text>
                            </v-card>

                            <div
                                :class="`text-${theme}-lighten-4`"
                                class="text-caption text-white position-absolute py-1 font-weight-bold text-uppercase"
                                style="top: 0; right: 0"
                            >
                                {{ title }}
                            </div>
                        </div>
                    </div>

                    <v-card class="mt-8 pt-8" rounded="lg" flat>
                        <slot></slot>
                    </v-card>

                    <div class="d-flex mt-4">
                        <slot name="actions" :isActive="isActive"></slot>
                    </div>
                </v-sheet>
            </v-card>
        </template>
    </v-dialog>
</template>

<script>
import { usePageStore } from "@pinia/pageStore";
import { storeToRefs } from "pinia";

export default {
    name: "form-confirm",

    props: {
        icon: {
            type: String,
            default: "power_settings_new",
        },

        title: {
            type: String,
            default: "keluar aplikasi",
        },
    },

    setup() {
        const store = usePageStore();

        const { highlight, theme } = storeToRefs(store);

        return {
            highlight,
            theme,
        };
    },
};
</script>
