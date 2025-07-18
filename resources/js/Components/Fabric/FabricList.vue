<script setup>
// Imports
import { ref } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

// Initialize toaster and get page props
const toaster = createToaster({});
const page = usePage();

// Table headers
const headers = [
    { text: "No", value: "id" },
    { text: "Unit", value: "unit" },
    { text: "Design Name", value: "dyeing.design_name" },
    { text: "Color", value: "dyeing.color" },
    { text: "Per Unit Cost", value: "per_unit_cost" },
    { text: "Total Cost", value: "total_cost" },
    { text: "Available Unit", value: "available_unit" },
    { text: "Roll", value: "roll" },
    { text: "Action", value: "action" },
];

// Table data from server
const items = ref(page.props.fabrics);

// Search
const searchField = ref("name");
const searchItem = ref();

// Flash messages from backend
if (page.props.flash.status === true) {
    toaster.success(page.props.flash.message);
} else if (page.props.flash.status === false) {
    toaster.error(page.props.flash.message);
}
</script>

<template>
    <!-- Page Title -->
    <p class="text-2xl font-bold mb-4">Fabric List</p>

    <!-- Top Controls: Search and Sale Button -->
    <div
        class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center mb-4"
    >
        <!-- Search Input -->
        <div class="w-full md:w-auto">
            <input
                type="text"
                v-model="searchItem"
                placeholder="Search by name"
                class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
            />
        </div>

        <!-- Sale Button -->
        <Link v-if="page.props.user.can['fabric-sale-page']"
            href="/fabric-sale-page"
            class="bg-blue-500 text-white font-bold py-1 px-6 rounded text-center md:inline-block"
        >
            Sale
        </Link>
    </div>

    <!-- Fabric Table -->
    <EasyDataTable
        :headers="headers"
        :items="items"
        alternating
        :rows-per-page="5"
        :search-field="searchField"
        :search-value="searchItem"
    >
        <!-- Action Column -->
        <template #item-action="{ id }">
            <Link v-if="page.props.user.can['cutting-save-page']"
                :href="`/cutting-save-page?dyeing_receive_id=${id}`"
                class="bg-blue-500 text-white font-bold py-2 px-4 rounded"
            >
                Add Cutting
            </Link>
        </template>
    </EasyDataTable>
</template>

<style scoped></style>
