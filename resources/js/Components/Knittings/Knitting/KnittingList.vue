<script setup>
import { ref } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import KnittingDetails from "./KnittingDetails.vue";

// Initialize toaster for notifications
const toaster = createToaster();

// Access Inertia page props
const page = usePage();

// Modal and selected item state
const modal = ref(false);
const knittingYarn = ref({});

// Table headers
const headers = [
    { text: "ID", value: "id" },
    { text: "Knitting Party Name", value: "knitting_party.name" },
    { text: "Weight", value: "total_unit" },
    { text: "Available Weight", value: "available_unit" },
    { text: "Per Unit Cost", value: "per_unit_cost" },
    { text: "Total Cost", value: "total_cost" },
    { text: "Action", value: "action" },
];

// Table items from backend
const items = ref(page.props.knittingList);


// Search functionality
const searchField = ref("name");
const searchItem = ref("");

// Show flash messages if available
if (page.props.flash.status === true) {
    toaster.success(page.props.flash.message);
} else if (page.props.flash.status === false) {
    toaster.error(page.props.flash.message);
}

// Open detail modal for selected item
function showModal(id) {
    knittingYarn.value = items.value.find((item) => item.id == id);
    modal.value = true;
}
</script>

<template>
    <!-- Knitting Details Modal -->
    <KnittingDetails :knittingYarn="knittingYarn" v-model:modal="modal" />

    <!-- Page Title -->
    <p class="text-2xl font-bold mb-4">Knitting List</p>

    <!-- Top Bar: Search + Add Button -->
    <div class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center mb-4">
        <!-- Search Field -->
        <div class="w-full md:w-auto">
            <input
                type="text"
                v-model="searchItem"
                placeholder="Search by name"
                class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
            />
        </div>

        <!-- Add Knitting Button -->
        <Link
            :href="`/knitting-save-page`"
            class="bg-blue-500 text-white font-bold py-1 px-4 rounded"
        >
            Add Knitting
        </Link>
    </div>

    <!-- Knitting Table -->
    <EasyDataTable
        :headers="headers"
        :items="items"
        :rows-per-page="5"
        :search-field="searchField"
        :search-value="searchItem"
        alternating
    >
        <!-- Action Buttons -->
        <template #item-action="{ id }">
            <div class="flex items-center space-x-2">
                <!-- View Button -->
                <button
                    type="button"
                    @click="showModal(id)"
                    class="border border-gray-700 text-gray-700 text-xs px-4 py-1 rounded hover:bg-gray-200 transition duration-300"
                >
                    <span class="material-icons text-sm">visibility</span>
                </button>

                <!-- Receive Button -->
                <Link
                    :href="`/knitting-receive-page?knitting_id=${id}`"
                    class="bg-blue-500 text-white font-bold py-2 px-4 rounded"
                >
                    Receive Knitting
                </Link>
            </div>
        </template>
    </EasyDataTable>
</template>

<style scoped></style>
