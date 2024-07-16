<template>
    <v-main>
        <div class="d-flex h-100">
            <div
                class="d-flex flex-column align-center justify-center pl-6"
                style="width: 500px"
            >
                <div
                    class="d-flex flex-column flex-grow-1 align-center justify-center"
                >
                    <div class="text-h6">Selamat datang di</div>

                    <div class="text-h3 font-brand mt-1">
                        MONO<span class="text-red">TECH</span>
                    </div>

                    <slot
                        :authenticate="authenticate"
                        :challenge="challenge"
                    ></slot>
                </div>

                <div class="text-overline"><small>versi 3.0</small></div>
            </div>

            <div
                class="d-flex align-center justify-center flex-grow-1 bg-blue-grey-lighten-3 position-relative"
            >
                <img
                    class="mt-auto h-100"
                    src="/assets/waves-haikei-left-1.svg"
                    style="position: absolute; left: 0; top: 0; z-index: 1"
                />

                <img src="/assets/pngegg.png" style="height: 75vh" />
            </div>
        </div>
    </v-main>
</template>

<script>
import { usePageStore } from "@pinia/pageStore";

export default {
    name: "PageWelcome",

    setup() {
        const store = usePageStore();

        const { authenticate, challenge, getSanctumCSRF } = store;

        return {
            authenticate,
            challenge,
            getSanctumCSRF,
        };
    },

    created() {
        this.getSanctumCSRF({ mobile: false });
    },
};
</script>
