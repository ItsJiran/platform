<template>
    <v-navigation-drawer
        v-model="sidenavState"
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
                    tabel {{ page.name }}
                </v-toolbar-title>

                <v-spacer></v-spacer>

                <v-btn
                    :color="helpState ? 'white' : `${theme}-lighten-3`"
                    icon
                    @click="
                        helpState = !helpState;
                        tabSidenav = helpState ? 'helpdesk' : 'filter';
                    "
                >
                    <v-icon
                        :style="
                            helpState
                                ? 'transform: rotate(180deg)'
                                : 'transform: rotate(0deg)'
                        "
                        >menu_open</v-icon
                    >
                </v-btn>
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
                            {{ helpState ? "informasi" : "utilitas" }}
                        </div>
                    </div>
                </div>

                <v-card class="mt-9 pt-9 mx-auto" rounded="lg" flat>
                    <v-tabs-window
                        class="overflow-hidden rounded-lg"
                        v-model="tabSidenav"
                    >
                        <v-tabs-window-item value="filter">
                            <div
                                v-if="usetrash"
                                class="text-overline text-grey-darken-3 px-3 mt-1"
                            >
                                <small>mode data</small>
                            </div>

                            <v-card-text v-if="usetrash" class="px-3 pt-0 pb-2">
                                <div
                                    class="d-flex align-center justify-center bg-grey-lighten-4 rounded-lg py-1"
                                >
                                    <div
                                        class="caption flex-grow-1 text-center"
                                        :class="
                                            trashed
                                                ? 'font-weight-normal text-grey'
                                                : 'font-weight-medium'
                                        "
                                    >
                                        Default
                                    </div>
                                    <v-switch
                                        :disabled="hasSelected"
                                        density="compact"
                                        v-model="trashed"
                                        hide-details
                                        inset
                                    ></v-switch>
                                    <div
                                        class="caption flex-grow-1 text-center"
                                        :class="
                                            trashed
                                                ? 'font-weight-medium'
                                                : 'font-weight-normal text-grey'
                                        "
                                    >
                                        Trashed
                                    </div>
                                </div>
                            </v-card-text>

                            <v-divider v-if="usetrash" class="my-2"></v-divider>

                            <div
                                class="text-overline text-grey-darken-3 px-3 mt-1"
                            >
                                <small>pencarian data</small>
                            </div>

                            <v-card-text class="px-3 pt-0 pb-2">
                                <v-text-field
                                    :disabled="hasSelected"
                                    density="comfortable"
                                    placeholder="Cari Data"
                                    v-model="search"
                                    clearable
                                    hide-details
                                ></v-text-field>
                            </v-card-text>

                            <v-divider
                                class="my-2"
                                v-if="
                                    filters && Object.keys(filters).length > 0
                                "
                            ></v-divider>

                            <template
                                v-for="(filter, indexFilter) in filters"
                                :key="indexFilter"
                            >
                                <div
                                    class="d-flex text-overline text-grey-darken-3 px-3 align-center mt-2"
                                >
                                    <small
                                        class="text-grey-darken-3 cursor-default"
                                        >filter {{ filter.title }}</small
                                    >

                                    <v-spacer></v-spacer>

                                    <v-menu :disabled="hasSelected">
                                        <template v-slot:activator="{ props }">
                                            <span
                                                :class="
                                                    hasSelected
                                                        ? 'text-grey-lighten-2'
                                                        : 'text-blue'
                                                "
                                                class="cursor-pointer mr-2"
                                                v-bind="props"
                                                >({{ filter.operator }})</span
                                            >
                                        </template>

                                        <v-list
                                            density="compact"
                                            v-model:selected="filter.operator"
                                            selectable
                                        >
                                            <v-list-item
                                                v-for="(
                                                    operator, index
                                                ) in filter.operators"
                                                :key="index"
                                                :title="operator"
                                                :value="operator"
                                                density="compact"
                                            >
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>

                                    <span class="mr-2 text-grey-lighten-2"
                                        >|</span
                                    >

                                    <v-btn
                                        class="text-white"
                                        :color="
                                            filter.used
                                                ? 'orange'
                                                : 'grey-lighten-2'
                                        "
                                        :disabled="
                                            !(
                                                filter.value &&
                                                filter.value !== ''
                                            )
                                        "
                                        density="compact"
                                        size="small"
                                        icon="cancel"
                                        flat
                                        @click="
                                            () => {
                                                filter.value = null;
                                                filter.used = false;
                                            }
                                        "
                                    ></v-btn>
                                </div>

                                <v-card-text class="px-3 pt-1 pb-2">
                                    <v-number-input
                                        v-if="filter.type === 'NumberInput'"
                                        :disabled="hasSelected"
                                        density="comfortable"
                                        control-variant="default"
                                        v-model="filter.value"
                                        @update:modelValue="
                                            () =>
                                                (filter.used =
                                                    filter.value &&
                                                    filter.value !== '')
                                        "
                                        hide-details
                                        inset
                                    ></v-number-input>

                                    <v-date-input
                                        v-if="filter.type === 'DateInput'"
                                        :disabled="hasSelected"
                                        density="comfortable"
                                        prepend-icon=""
                                        v-model="filter.value"
                                        @update:modelValue="
                                            () =>
                                                (filter.used =
                                                    filter.value &&
                                                    filter.value !== '')
                                        "
                                        hide-details
                                        readonly
                                    ></v-date-input>

                                    <v-select
                                        v-if="filter.type === 'Select'"
                                        :disabled="hasSelected"
                                        :items="filter.data"
                                        density="comfortable"
                                        v-model="filter.value"
                                        hide-details
                                        @update:modelValue="
                                            () =>
                                                (filter.used =
                                                    filter.value &&
                                                    filter.value !== '')
                                        "
                                    ></v-select>
                                </v-card-text>
                            </template>

                            <slot name="utility"></slot>

                            <v-card-text class="flex-grow-1 bg-white">
                                <v-btn
                                    :color="theme"
                                    :disabled="hasSelected"
                                    rounded="pill"
                                    block
                                    flat
                                    @click="applyFilterData"
                                    >terapkan filter</v-btn
                                >
                            </v-card-text>
                        </v-tabs-window-item>

                        <v-tabs-window-item value="helpdesk">
                            <v-card-text class="pt-3">
                                <div class="text-overline">Form Data</div>
                                <v-divider></v-divider>

                                <div
                                    class="text-caption text-grey-darken-1 pt-2"
                                >
                                    <slot name="forminfo"
                                        >Form ini berfungsi untuk menampilkan
                                        semua data dalam tabel.</slot
                                    >
                                </div>

                                <div class="text-overline mt-6">Utilitas</div>
                                <v-divider></v-divider>

                                <table
                                    class="w-100"
                                    style="border-spacing: 0px"
                                >
                                    <colgroup>
                                        <col width="48px" />
                                        <col width="auto" />
                                    </colgroup>

                                    <tbody>
                                        <tr v-if="usetrash">
                                            <td
                                                style="
                                                    padding-top: 16px;
                                                    vertical-align: baseline;
                                                "
                                            >
                                                <v-avatar
                                                    size="32"
                                                    :color="`${theme}-lighten-1`"
                                                >
                                                    <v-icon size="small"
                                                        >toggle_off</v-icon
                                                    >
                                                </v-avatar>
                                            </td>

                                            <td style="padding-top: 16px">
                                                <span
                                                    class="text-caption font-weight-medium"
                                                    >Data Mode</span
                                                >
                                                <span
                                                    class="d-block text-caption text-grey-darken-1"
                                                    >utilitas ini berfungsi
                                                    untuk beralih ke data
                                                    trashed ON atau OFF, trashed
                                                    berfungsi untuk menampilkan
                                                    data yang telah di hapus
                                                    sebelumnya.</span
                                                >
                                            </td>
                                        </tr>

                                        <tr>
                                            <td
                                                style="
                                                    padding-top: 16px;
                                                    vertical-align: baseline;
                                                "
                                            >
                                                <v-avatar
                                                    size="32"
                                                    :color="`${theme}-lighten-1`"
                                                >
                                                    <v-icon size="small"
                                                        >text_fields</v-icon
                                                    >
                                                </v-avatar>
                                            </td>

                                            <td style="padding-top: 16px">
                                                <span
                                                    class="text-caption font-weight-medium"
                                                    >Pencarian</span
                                                >
                                                <span
                                                    class="d-block text-caption text-grey-darken-1"
                                                    >utilitas ini berfungsi
                                                    untuk melakukan pencarian
                                                    data berdasarkan
                                                    input.</span
                                                >
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <slot name="helpdesk"></slot>

                                <div class="text-overline mt-6">Icon</div>
                                <v-divider></v-divider>

                                <table
                                    class="w-100"
                                    style="border-spacing: 0px"
                                >
                                    <colgroup>
                                        <col width="48px" />
                                        <col width="auto" />
                                    </colgroup>

                                    <tbody>
                                        <tr>
                                            <td
                                                style="
                                                    padding-top: 16px;
                                                    vertical-align: baseline;
                                                "
                                            >
                                                <v-avatar
                                                    size="32"
                                                    :color="`${theme}-lighten-1`"
                                                >
                                                    <v-icon size="small"
                                                        >menu</v-icon
                                                    >
                                                </v-avatar>
                                            </td>

                                            <td style="padding-top: 16px">
                                                <span
                                                    class="text-caption font-weight-medium"
                                                    >Rail Mode</span
                                                >
                                                <span
                                                    class="d-block text-caption text-grey-darken-1"
                                                    >icon ini berfungsi untuk
                                                    beralih tampilan panel menu
                                                    ke mode rail ON atau
                                                    OFF.</span
                                                >
                                            </td>
                                        </tr>

                                        <tr>
                                            <td
                                                style="
                                                    padding-top: 16px;
                                                    vertical-align: baseline;
                                                "
                                            >
                                                <v-avatar
                                                    size="32"
                                                    :color="`${theme}-lighten-1`"
                                                >
                                                    <v-icon
                                                        color="white"
                                                        size="small"
                                                        >filter_list</v-icon
                                                    >
                                                </v-avatar>
                                            </td>

                                            <td style="padding-top: 16px">
                                                <span
                                                    class="text-caption font-weight-medium"
                                                    >Filter</span
                                                >
                                                <span
                                                    class="d-block text-caption text-grey-darken-1"
                                                    >icon ini berfungsi untuk
                                                    membuka atau menutup panel
                                                    filter, yang berisi
                                                    pencarian atau filter data
                                                    pada tabel.</span
                                                >
                                            </td>
                                        </tr>

                                        <tr>
                                            <td
                                                style="
                                                    padding-top: 16px;
                                                    vertical-align: baseline;
                                                "
                                            >
                                                <v-avatar
                                                    size="32"
                                                    :color="`${theme}-lighten-1`"
                                                >
                                                    <v-icon
                                                        color="white"
                                                        size="small"
                                                        >menu_open</v-icon
                                                    >
                                                </v-avatar>
                                            </td>

                                            <td style="padding-top: 16px">
                                                <span
                                                    class="text-caption font-weight-medium"
                                                    >Informasi</span
                                                >
                                                <span
                                                    class="d-block text-caption text-grey-darken-1"
                                                    >icon ini berfungsi untuk
                                                    membuka atau menutup panel
                                                    informasi, yang berisi
                                                    petunjuk atas form.</span
                                                >
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </v-card-text>
                        </v-tabs-window-item>
                    </v-tabs-window>
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
    name: "page-filter",

    setup() {
        const store = usePageStore();

        const {
            filters,
            hasSelected,
            helpState,
            page,
            params,
            sidenavState,
            search,
            trashed,
            theme,
            usetrash,
        } = storeToRefs(store);
        const { getPageDatas } = store;

        return {
            filters,
            hasSelected,
            helpState,
            page,
            params,
            sidenavState,
            search,
            trashed,
            theme,
            usetrash,

            getPageDatas,
        };
    },

    data: () => ({
        tabSidenav: "filter",
    }),

    methods: {
        applyFilterData: function () {
            this.params.page = 1;

            this.getPageDatas(this.params);
        },
    },
};
</script>
