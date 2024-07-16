<template>
    <v-toolbar :color="theme">
        <v-btn
            icon
            v-if="parentName"
            @click="$router.push({ name: parentName })"
        >
            <v-icon>arrow_back</v-icon>
        </v-btn>

        <v-toolbar-title class="text-body-2 font-weight-bold text-uppercase">{{
            module.name
        }}</v-toolbar-title>

        <v-spacer></v-spacer>

        <v-btn
            v-if="!disableCreate && !hasSelected"
            :color="highlight"
            icon
            @click="openFormCreate"
        >
            <v-icon class="with-shadow">add</v-icon>
        </v-btn>

        <v-btn
            :disabled="hasSelected"
            :color="sidenavState ? 'white' : `${theme}-lighten-3`"
            icon
            @click="sidenavState = !sidenavState"
        >
            <v-icon class="with-shadow">filter_list</v-icon>
        </v-btn>
    </v-toolbar>

    <v-sheet
        :color="`${theme}`"
        class="mx-auto position-absolute w-100 rounded-b-xl"
        height="192"
    ></v-sheet>

    <v-responsive
        :height="navigationState ? `calc(100vh - 120px)` : `calc(100vh - 64px)`"
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
                    class="position-absolute"
                    style="top: 0; left: 0; line-height: 1"
                >
                    <v-chip
                        v-if="title"
                        :color="`${theme}-lighten-1`"
                        class="text-uppercase font-weight-bold"
                        size="x-small"
                        variant="flat"
                        style="
                            font-size: 8px;
                            letter-spacing: 0.3px;
                            text-shadow: 0px 0.5px 0.5px rgba(0, 0, 0, 0.3);
                        "
                        >{{ title }}</v-chip
                    >
                </div>

                <div
                    :class="`text-${theme}-lighten-4`"
                    class="text-caption text-white position-absolute font-weight-bold text-uppercase"
                    style="font-size: 0.7rem !important; top: 0; right: 0"
                >
                    {{ page.name }}
                </div>
            </div>
        </v-sheet>

        <v-sheet
            class="mt-7 pt-7"
            elevation="1"
            rounded="lg"
            min-height="calc(100% - 44px)"
        >
            <v-list
                class="bg-transparent"
                :active-class="
                    showDelete
                        ? `bg-white elevation-4 text-grey with-delete`
                        : `bg-white elevation-4 text-grey`
                "
                lines="two"
                selectable
                @update:selected="setSelected"
            >
                <template v-for="(record, index) in records">
                    <slot
                        name="mobile"
                        :index="index"
                        :record="record"
                        :showDelete="showDelete"
                        :theme="theme"
                    >
                        <item-data
                            :show-delete="showDelete"
                            :value="record"
                        ></item-data>
                    </slot>
                </template>

                <template v-if="records.length <= 0">
                    <slot>
                        <div
                            class="d-flex align-center justify-center text-body-2 text-center text-grey"
                            style="height: calc(100vh - 208px)"
                        >
                            Data tidak ditemukan
                        </div>
                    </slot>
                </template>

                <template v-else>
                    <div
                        v-if="
                            meta.current_page &&
                            meta.current_page < meta.last_page
                        "
                        class="d-flex align-center justify-center py-2"
                        style="width: 100%"
                        v-intersect="onIntersect"
                    ></div>
                </template>
            </v-list>
        </v-sheet>

        <div class="py-2"></div>
    </v-responsive>

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
            meta,
            module,
            navigationState,
            page,
            pageKey,
            params,
            parentName,
            records,
            railMode,
            sidenavState,
            selected,
            title,
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
            meta,
            module,
            navigationState,
            page,
            pageKey,
            params,
            parentName,
            records,
            railMode,
            sidenavState,
            selected,
            title,
            theme,
            totalRecords,

            getPageDatas,
            openFormCreate,
            openFormShow,
            setSelected,
        };
    },

    methods: {
        onIntersect: function (isIntersecting) {
            if (isIntersecting) {
                this.loading = true;
                this.params.page = this.params.page + 1;
            }
        },
    },

    watch: {
        params: {
            handler: function (newOptions) {
                this.getPageDatas(newOptions);
            },

            deep: true,
            immediate: true,
        },
    },
};
</script>
