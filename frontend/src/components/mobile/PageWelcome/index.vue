<template>
    <v-main>
        <div class="position-absolute" style="top: 0; width: 100%">
            <v-sheet
                class="rounded-b-xl"
                :color="`${theme}-lighten-3`"
                height="380"
            ></v-sheet>
        </div>

        <v-sheet class="position-relative" color="transparent" height="100vh">
            <v-card-text style="height: 100vh">
                <v-card-text
                    class="d-flex flex-column align-center justify-center mt-12 mb-4"
                >
                    <img
                        src="/assets/pwa-192x192.png"
                        style="height: 13vh; width: 12vh; position: absolute"
                    />
                </v-card-text>

                <v-card-text class="pb-2">
                    <div class="text-h6 text-center mb-4">
                        Selamat datang di
                    </div>

                    <div class="text-h3 font-brand text-center">
                        SiMAS<span class="text-red">TEN</span>
                    </div>

                    <div class="text-overline text-center">
                        <small>versi 3.0</small>
                    </div>
                </v-card-text>

                <!-- d-flex flex-column align-center justify-center  -->
                <v-sheet
                    elevation="6"
                    rounded="lg"
                    class="mx-auto overflow-hidden"
                    max-width="343"
                    height="384"
                >
                    <slot
                        :authenticate="authenticate"
                        :challenge="challenge"
                        :finduser="finduser"
                        :resetpass="resetpass"
                    ></slot>
                </v-sheet>
            </v-card-text>
        </v-sheet>
    </v-main>
</template>

<script>
import { usePageStore } from "@pinia/pageStore";

export default {
    name: "PageWelcome",

    setup() {
        const store = usePageStore();

        const {
            authenticate,
            challenge,
            finduser,
            getSanctumCSRF,
            resetpass,
            theme,
        } = store;

        return {
            authenticate,
            challenge,
            finduser,
            getSanctumCSRF,
            resetpass,
            theme,
        };
    },

    created() {
        this.getSanctumCSRF({ mobile: true });
    },
};
</script>
