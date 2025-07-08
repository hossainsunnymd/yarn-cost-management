<script setup>
import { ref } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import KnittingDetails from "./KnittingDetails.vue";

const toaster = createToaster({});
const page = usePage();
const modal = ref(false);
const knittingYarn = ref({});

const headers = [
    { text: "ID", value: "id" },
    { text: "knitting Party Name", value: "knitting_party.name" },
    { text: "Weight", value: "total_unit" },
    { text: "Available Weight", value: "available_unit" },
    { text: "Per Unit Cost", value: "per_unit_cost" },
    { text: "Total Cost", value: "total_cost" },
    { text: "Action", value: "action" },
];

const items = ref(page.props.knittingList);
const searchField = ref("name");
const searchItem = ref();

if (page.props.flash.status == true) {
    toaster.success(page.props.flash.message);
} else if (page.props.flash.status == false) {
    toaster.error(page.props.flash.message);
}

function showModal(id) {
    knittingYarn.value = items.value.find((item) => item.id == id);
    modal.value = true;
}
</script>

<template>
    <KnittingDetails :knittingYarn="knittingYarn" v-model:modal="modal"/>
    <p class="text-2xl font-bold">Knitting List</p>
    <div
        class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center"
    >
        <div class="w-full md:w-auto">
            <input
                type="text"
                class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
                v-model="searchItem"
                placeholder="Search by name"
            />
        </div>
        <Link
            :href="`/knitting-save-page`"
            class="bg-blue-500 text-white font-bold py-1 px-4 rounded"
            >Add Knitting</Link
        >
    </div>

    <EasyDataTable
        :headers="headers"
        :items="items"
        alternating
        :rows-per-page="5"
        :search-field="searchField"
        :search-value="searchItem"
    >
        <template #item-action="{ id }">
          <div class="flex items-center">
              <button
                type="button"
                class="border border-gray-700 text-gray-700 text-xs mr-1 px-4 py-1 rounded hover:bg-gray-200 transition duration-300"
                @click="showModal(id)"
            >
                <span class="material-icons text-sm">visibility</span>
            </button>
            <Link
                :href="`/knitting-receive-page?knitting_id=${id}`"
                class="bg-blue-500 text-white font-bold py-2 px-4 rounded"
                >Receive Knitting</Link
            >
          </div>
        </template>
    </EasyDataTable>
</template>

<style scoped></style>
