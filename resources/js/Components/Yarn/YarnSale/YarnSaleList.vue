<script setup>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({});
const page = usePage();

// Table headers definition for EasyDataTable component
const headers = [
    { text: "No", value: "id" },
    { text: "Unit", value: "unit" },
    { text: "Price", value: "total_amount" },
];

// Yarn Sale list
const items = ref(page.props.yarnSaleList);

// Search functionality
const searchField = ref("name");
const searchItem = ref("");
</script>

<template>
    <p class="text-2xl font-bold mb-4">Yarn Sale List</p>

    <!-- Search input -->
    <div
        class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center mb-4"
    >
        <div class="w-full md:w-auto">
            <input
                type="text"
                class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
                v-model="searchItem"
                placeholder="Search by name"
            />
        </div>
    </div>

    <!-- Data table component to display yarn sale items -->
    <EasyDataTable
        :headers="headers"
        :items="items"
        alternating
        :rows-per-page="5"
        :search-field="searchField"
        :search-value="searchItem"
    />
</template>
