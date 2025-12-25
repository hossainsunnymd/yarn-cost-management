<script setup>
import { ref } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import { createToaster } from "@meforma/vue-toaster";

import KnittingSaleDetails from './KnittingSaleDetails.vue';

//  toaster for notifications
const toaster = createToaster({});
const page = usePage();
const modal = ref(false);
const knittings = ref({});

// Table headers
const headers = [
  { text: 'No', value: 'id' },
  { text: 'Sale Date', value: 'sale_date' },
  { text: 'Challan No', value: 'challan_no' },
  { text: 'Total Weight', value: 'unit' },
  { text: 'Total Amount', value: 'total_amount' },
  { text: 'Action', value: 'action' },
];


// handle props for data table
const items = ref(page.props.knittingSaleList);

//search field
const searchField = ref("name");

// search input
const searchItem = ref();

const showModal = (id) => {
    knittings.value=items.value.find((item) => item.id == id);
    modal.value = true;
}

const deleteKnittingSale = (id) => {
    if(confirm("Are you sure you want to delete this knitting sale?")) {
        
    }
}
</script>

<template>

    <!-- Page title -->
    <p class="text-2xl font-bold">Knitting Sale List</p>

    <!-- Search input and layout -->
    <div class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center">
        <div class="w-full md:w-auto">
            <input
                type="text"
                class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
                v-model="searchItem"
                placeholder="Search by name"
            />
        </div>
        <div>

        </div>
    </div>

    <!-- Data table  -->
    <EasyDataTable
        :headers="headers"
        :items="items"
        alternating
        :rows-per-page="5"
        :search-field="searchField"
        :search-value="searchItem"
    >

            <template #item-action="{ id }">
            <div class="flex gap-2">
                <button
                    @click="deleteKnittingSale(id)"
                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-4 rounded-md transition"
                >
                    Delete
                </button>
                <button
                    @click="showModal(id)"
                    type="button"
                    class="border border-gray-700 text-gray-700 font-bold py-1 px-2 rounded-md transition text-sm"
                >
                    <span class="material-icons">visibility</span>
                </button>
            </div>
        </template>

    </EasyDataTable>
</template>


