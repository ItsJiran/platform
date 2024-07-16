<template>
    <v-sheet color="transparent" class="position-relative">
        <v-toolbar :color="theme">
            <v-app-bar-nav-icon
                @click="railMode = !railMode"
            ></v-app-bar-nav-icon>

            <v-toolbar-title
                class="text-body-2 font-weight-bold text-uppercase"
                >{{ module.name }}</v-toolbar-title
            >

            <v-spacer></v-spacer>

            <v-btn
                v-if="!disableCreate && !hasSelected"
                :color="highlight"
                icon="add"
                @click="openFormCreate"
            ></v-btn>

            <v-btn
                icon="folder_open"
                v-if="hasSelected"
                @click="openFormShow"
            ></v-btn>

            <v-btn
                :color="highlight"
                icon="delete"
                v-if="hasSelected && showDelete"
            ></v-btn>

            <v-btn
                :disabled="hasSelected"
                :color="sidenavState ? 'white' : `${theme}-lighten-3`"
                icon="filter_list"
                @click="sidenavState = !sidenavState"
            ></v-btn>
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
                        tabel {{ page.name }}
                    </div>
                </div>
            </div>

            <v-card
                :style="`max-width: ${maxWidth}`"
                class="mt-9 pt-9 mx-auto"
                rounded="lg"
                flat
            >
                <v-data-table-server
                    v-model="selected"
                    :headers="headers"
                    :items="records"
                    :items-length="totalRecords"
                    :items-per-page="itemsPerPage"
                    :loading="loading"
                    density="comfortable"
                    item-value="name"
                    select-strategy="single"
                    @click:row="clickOnRow"
                    @update:options="loadItems"
                    return-object
                    show-select
                ></v-data-table-server>
            </v-card>
        </v-sheet>
    </v-sheet>

    <page-filter>
        <template v-slot:forminfo>
            <slot name="forminfo" :theme="theme"></slot>
        </template>

        <template v-slot:helpdesk>
            <slot name="helpdesk" :theme="theme"></slot>
        </template>

        <template v-slot:utility>
            <slot name="utility" :theme="theme"></slot>
        </template>
    </page-filter>
</template>

<script>
import { usePageStore } from "@pinia/pageStore";
import { storeToRefs } from "pinia";

export default {
    name: "form-data",

    props: {
        disableCreate: Boolean,

        maxWidth: {
            type: String,
            default: "900px",
        },

        showDelete: {
            type: Boolean,
            default: false,
        },
    },

    setup() {
        const store = usePageStore();

        store.helpState = false;

        const {
            formStateLast,
            hasSelected,
            headers,
            highlight,
            itemsPerPage,
            loading,
            module,
            page,
            pageKey,
            params,
            records,
            railMode,
            sidenavState,
            selected,
            theme,
            totalRecords,
        } = storeToRefs(store);

        const { getPageDatas, openFormCreate, openFormShow, setSelected } =
            store;

        return {
            formStateLast,
            hasSelected,
            headers,
            highlight,
            itemsPerPage,
            loading,
            module,
            page,
            pageKey,
            params,
            records,
            railMode,
            sidenavState,
            selected,
            theme,
            totalRecords,

            getPageDatas,
            openFormCreate,
            openFormShow,
            setSelected,
        };
    },

    methods: {
        clickOnRow: function (event, { item }) {
            this.setSelected(item);
        },

        loadItems: function (tableOptions) {
            this.getPageDatas(tableOptions);
        },
    },
};
</script>
