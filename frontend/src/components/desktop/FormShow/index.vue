<template>
    <v-sheet color="transparent" class="position-relative">
        <v-toolbar :color="theme">
            <v-btn icon="arrow_back" @click="openFormData"></v-btn>

            <v-toolbar-title
                class="text-body-2 font-weight-bold text-uppercase"
                >{{ page.name }}</v-toolbar-title
            >

            <v-spacer></v-spacer>

            <template v-if="softdelete">
                <v-btn icon>
                    <v-icon>restore</v-icon>

                    <form-confirm
                        icon="restore"
                        title="pemulihan"
                        message="Proses ini akan memulihkan data ini dari status trashed."
                    >
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
                                :color="`${theme}`"
                                class="text-white"
                                variant="flat"
                                @click="
                                    postFormRestore(
                                        () => (isActive.value = false)
                                    )
                                "
                                >Pulihkan</v-btn
                            >
                        </template>
                    </form-confirm>

                    <v-tooltip activator="parent" location="bottom"
                        >Pulihkan</v-tooltip
                    >
                </v-btn>

                <v-btn color="orange" icon>
                    <v-icon>delete_forever</v-icon>

                    <form-confirm
                        icon="delete_forever"
                        title="hapus permanent"
                        message="Proses ini akan menghapus data secara permanen, proses ini tidak dapat di pulihkan setelah di lakukan."
                    >
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
                                :color="`${theme}`"
                                class="text-white"
                                variant="flat"
                                @click="
                                    postFormForceDelete(
                                        () => (isActive.value = false)
                                    )
                                "
                                >Hapus</v-btn
                            >
                        </template>
                    </form-confirm>

                    <v-tooltip activator="parent" location="bottom"
                        >Hapuskan</v-tooltip
                    >
                </v-btn>
            </template>

            <template v-else>
                <v-btn v-if="!hideEdit" icon @click="openFormEdit">
                    <v-icon>edit</v-icon>

                    <v-tooltip activator="parent" location="bottom"
                        >Ubah</v-tooltip
                    >
                </v-btn>

                <v-btn v-if="!hideDelete" color="orange" icon>
                    <v-icon>delete</v-icon>

                    <form-confirm
                        icon="delete"
                        title="hapus"
                        message="Proses ini akan juga menghapus semua data yang terkait pada data ini."
                    >
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
                                :color="`${theme}`"
                                class="text-white"
                                variant="flat"
                                @click="
                                    postFormDelete(
                                        () => (isActive.value = false)
                                    )
                                "
                                >Hapus</v-btn
                            >
                        </template>
                    </form-confirm>

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
                        show
                    </div>
                </div>
            </div>

            <v-sheet
                :style="`max-width: ${maxWidth}`"
                class="mt-9 pt-9 mx-auto"
                min-height="calc(100vh - 175px)"
                rounded="lg"
            >
                <slot :combos="combos" :record="record" :theme="theme"></slot>
            </v-sheet>
        </v-sheet>
    </v-sheet>

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

        maxWidth: {
            type: String,
            default: "560px",
        },

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

    created() {
        this.getPageData(this.dataFromStore);
    },
};
</script>
