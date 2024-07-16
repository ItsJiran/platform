<template>
    <v-navigation-drawer
        v-model="helpState"
        color="transparent"
        location="right"
        style="height: calc(100vh - 8px); padding-right: 4px"
        width="356"
        disable-resize-watcher
    >
        <v-sheet
            class="overflow-hidden position-relative"
            color="transparent"
            height="calc(100vh - 8px)"
            rounded="lg"
        >
            <v-toolbar :color="theme">
                <v-toolbar-title class="text-white text-overline">
                    form {{ page.name }}
                </v-toolbar-title>

                <v-spacer></v-spacer>
            </v-toolbar>

            <v-sheet
                :color="`${theme}-lighten-4`"
                class="position-absolute w-100"
                height="100%"
            ></v-sheet>

            <v-sheet
                :color="`${theme}`"
                class="position-absolute w-100"
                height="192"
                rounded="b-xl"
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
                        class="d-flex flex-column align-center justify-center position-relative mx-auto"
                    >
                        <v-card :color="`${theme}`" rounded="pill">
                            <v-card-text class="pa-1">
                                <v-avatar
                                    :color="`${theme}-lighten-2`"
                                    size="64"
                                    style="font-size: 22px"
                                >
                                    <v-icon :color="`${theme}-darken-1`">{{
                                        helpState ? "menu_open" : "filter_list"
                                    }}</v-icon>
                                </v-avatar>
                            </v-card-text>
                        </v-card>

                        <div
                            :class="`text-${theme}-lighten-4`"
                            class="text-caption text-white position-absolute pt-3 font-weight-bold text-uppercase"
                            style="top: 0; right: 0"
                        >
                            informasi
                        </div>
                    </div>
                </div>

                <v-card class="mt-9 pt-9 mx-auto" rounded="lg" flat>
                    <v-card-text>
                        <div class="text-overline">Form</div>
                        <v-divider></v-divider>

                        <div class="text-caption text-grey-darken-1 pt-2">
                            <slot name="forminfo"
                                >Form ini berfungsi untuk menampilkan form
                                data.</slot
                            >
                        </div>

                        <slot name="helpdesk"></slot>

                        <widget-icon :mode="mode"></widget-icon>

                        <widget-activity
                            v-if="withActivityLogs"
                        ></widget-activity>
                    </v-card-text>
                </v-card>

                <div class="py-2"></div>
            </v-sheet>
        </v-sheet>
    </v-navigation-drawer>
</template>

<script>
import { usePageStore } from "@pinia/pageStore";
import { storeToRefs } from "pinia";

export default {
    name: "form-help",

    props: {
        mode: {
            type: String,
            default: "show",
        },

        withActivityLogs: Boolean,
    },

    setup() {
        const store = usePageStore();

        const { helpState, logs, page, theme } = storeToRefs(store);

        return {
            helpState,
            logs,
            page,
            theme,
        };
    },
};
</script>
