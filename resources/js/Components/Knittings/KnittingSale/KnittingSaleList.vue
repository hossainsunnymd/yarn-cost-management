<script setup>
import { ref } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import { createToaster } from "@meforma/vue-toaster";

//  toaster for notifications
const toaster = createToaster({});
const page = usePage();

// Table headers
const headers = [
  { text: 'No', value: 'id' },
  { text: 'Unit', value: 'unit' },
  { text: 'Price', value: 'total_amount' },
  { text: "Knitting Sale date", value: "created_at" },
];

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-GB");
};

// handle props for data table
const items = ref(page.props.knittingSaleList);

//search field
const searchField = ref("name");

// search input
const searchItem = ref();
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

        <!-- Date Format -->
        <template #item-created_at="{ created_at }">
            {{ formatDate(created_at) }}
        </template>

    </EasyDataTable>
</template>


