<template>
    <v-navigation-drawer
        v-model="helpState"
        :color="`${theme}-lighten-4`"
        location="right"
        width="360"
        disable-resize-watcher
    >
        <v-sheet class="position-relative" color="transparent" height="100vh">
            <v-toolbar :color="theme">
                <v-btn icon @click="helpState = false">
                    <v-icon>close</v-icon>
                </v-btn>

                <v-toolbar-title class="text-white text-overline">
                    {{ page.name }}
                </v-toolbar-title>
            </v-toolbar>

            <v-sheet
                :color="`${theme}`"
                class="mx-auto position-absolute rounded-b-xl"
                height="192"
                width="360"
            ></v-sheet>

            <v-responsive
                height="calc(100vh - 64px)"
                class="bg-transparent overflow-x-hidden overflow-y-auto px-4"
                content-class="position-relative"
            >
                <v-sheet
                    class="position-absolute text-center w-100"
                    color="transparent"
                    style="z-index: 1"
                >
                    <div class="d-flex justify-center position-relative">
                        <v-sheet
                            :color="`${theme}`"
                            elevation="4"
                            rounded="pill"
                        >
                            <v-card-text class="pa-1">
                                <v-avatar
                                    :color="`${theme}-lighten-2`"
                                    size="48"
                                    style="font-size: 22px"
                                >
                                    <v-icon :color="`${theme}-darken-1`"
                                        >menu_open</v-icon
                                    >
                                </v-avatar>
                            </v-card-text>
                        </v-sheet>

                        <div
                            :class="`text-${theme}-lighten-4`"
                            class="text-caption text-white position-absolute py-1 font-weight-bold text-uppercase"
                            style="
                                font-size: 0.7rem !important;
                                top: 0;
                                right: 0;
                            "
                        >
                            informasi
                        </div>
                    </div>
                </v-sheet>

                <v-sheet
                    class="mt-7 pt-7"
                    elevation="1"
                    min-height="200px"
                    rounded="lg"
                >
                    <v-card-text>
                        <div class="text-overline">Form</div>
                        <v-divider></v-divider>

                        <div class="text-caption text-grey-darken-1 pt-2">
                            <slot name="forminfo"
                                >Form ini berfungsi untuk menampilkan form
                                data.</slot
                            >
                        </div>

                        <slot name="helpdesk" :theme="theme"></slot>

                        <widget-icon :mode="mode"></widget-icon>

                        <widget-activity
                            v-if="withActivityLogs"
                        ></widget-activity>
                    </v-card-text>
                </v-sheet>

                <div class="py-2"></div>
            </v-responsive>
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

<style scoped>
.v-navigation-drawer--right {
    border-left-width: 0;
}
</style>
