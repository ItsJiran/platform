<template>
    <v-toolbar :color="theme">
        <v-btn icon @click="openFormData">
            <v-icon class="with-shadow">arrow_back</v-icon>
        </v-btn>

        <v-toolbar-title class="text-body-2 font-weight-bold text-uppercase">{{
            page.name
        }}</v-toolbar-title>

        <v-spacer></v-spacer>

        <template v-if="softdelete">
            <v-btn icon>
                <v-icon class="with-shadow">restore</v-icon>

                <form-confirm icon="restore" title="pulihkan">
                    <v-card-text>
                        Proses ini akan memulihkan data ini dari status trashed.
                    </v-card-text>

                    <template v-slot:actions="{ isActive }">
                        <v-spacer></v-spacer>

                        <v-btn
                            :color="`${theme}-lighten-2`"
                            class="text-white mr-2"
                            variant="flat"
                            @click="isActive.value = false"
                            >batal</v-btn
                        >

                        <v-btn
                            color="deep-orange"
                            text="Pulihkan"
                            @click="
                                postFormRestore(() => (isActive.value = false))
                            "
                        ></v-btn>
                    </template>
                </form-confirm>
            </v-btn>

            <v-btn color="orange" icon>
                <v-icon class="with-shadow">delete_forever</v-icon>

                <form-confirm icon="delete_forever" title="Hapus Permanen?">
                    <v-card-text>
                        Proses ini akan menghapus data secara permanen, proses
                        ini tidak dapat di pulihkan setelah di lakukan.
                    </v-card-text>

                    <template v-slot:actions="{ isActive }">
                        <v-spacer></v-spacer>

                        <v-btn
                            :color="`${theme}-lighten-2`"
                            class="text-white mr-2"
                            variant="flat"
                            @click="isActive.value = false"
                            >batal</v-btn
                        >

                        <v-btn
                            color="deep-orange"
                            text="Hapus"
                            @click="
                                postFormForceDelete(
                                    () => (isActive.value = false)
                                )
                            "
                        ></v-btn>
                    </template>
                </form-confirm>
            </v-btn>
        </template>

        <template v-else>
            <v-btn v-if="!hideEdit" icon @click="openFormEdit">
                <v-icon class="with-shadow">edit</v-icon>

                <v-tooltip activator="parent" location="bottom">Ubah</v-tooltip>
            </v-btn>

            <v-btn v-if="!hideDelete" color="orange" icon>
                <v-icon class="with-shadow">delete</v-icon>

                <v-dialog activator="parent" max-width="360" persistent>
                    <template v-slot:default="{ isActive }">
                        <v-card
                            prepend-icon="delete"
                            text="Proses ini akan juga menghapus semua data yang terkait pada data ini."
                            title="Hapus data ini?"
                        >
                            <template v-slot:actions>
                                <v-spacer></v-spacer>

                                <v-btn
                                    color="grey"
                                    text="Batal"
                                    @click="isActive.value = false"
                                ></v-btn>

                                <v-btn
                                    color="deep-orange"
                                    text="Hapus"
                                    @click="
                                        postFormDelete(
                                            () => (isActive.value = false)
                                        )
                                    "
                                ></v-btn>
                            </template>
                        </v-card>
                    </template>
                </v-dialog>

                <v-tooltip activator="parent" location="bottom"
                    >Hapus</v-tooltip
                >
            </v-btn>
        </template>

        <v-btn
            v-if="withHelpdesk"
            :color="helpState ? 'white' : `${theme}-lighten-3`"
            icon
            @click="helpState = !helpState"
        >
            <v-icon
                class="with-shadow"
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
        :color="`${theme}`"
        class="mx-auto position-absolute w-100 rounded-b-xl"
        height="192"
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
                <v-sheet :color="`${theme}`" elevation="4" rounded="pill">
                    <v-card-text class="pa-1">
                        <v-avatar
                            :color="`${highlight}-lighten-2`"
                            size="48"
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
                    class="text-caption text-white position-absolute py-1 font-weight-bold text-uppercase"
                    style="font-size: 0.7rem !important; top: 0; right: 0"
                >
                    show: {{ record[key] }}
                </div>
            </div>
        </v-sheet>

        <v-sheet
            class="mt-7 pt-7"
            min-height="200px"
            elevation="1"
            rounded="lg"
        >
            <slot :combos="combos" :record="record" :theme="theme"></slot>
        </v-sheet>

        <div class="py-2"></div>
    </v-responsive>

    <form-help mode="show" :withActivityLogs="withActivityLogs">
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
    name: "form-show",

    props: {
        beforePost: Function,
        blankForm: Boolean,
        contentClass: String,
        dataFromStore: Boolean,
        hideEdit: Boolean,
        hideDelete: Boolean,
        width: {
            type: String,
            default: "500px",
        },
        withHelpdesk: Boolean,
        withActivityLogs: Boolean,
    },

    setup(props) {
        const store = usePageStore();

        store.beforePost = props.beforePost;
        store.activityLog = props.withActivityLogs;

        const {
            combos,
            helpState,
            highlight,
            key,
            navigationState,
            page,
            pageKey,
            softdelete,
            record,
            theme,
        } = storeToRefs(store);

        const {
            getPageData,
            openFormData,
            openFormEdit,
            postFormDelete,
            postFormForceDelete,
            postFormRestore,
        } = store;

        return {
            combos,
            helpState,
            highlight,
            key,
            navigationState,
            page,
            pageKey,
            record,
            softdelete,
            theme,

            getPageData,
            openFormData,
            openFormEdit,
            postFormDelete,
            postFormForceDelete,
            postFormRestore,
        };
    },

    mounted() {
        this.getPageData(this.dataFromStore);
    },
};
</script>
