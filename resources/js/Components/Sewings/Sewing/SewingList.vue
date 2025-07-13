<script setup>
import { ref } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

// Initialize toaster for notifications
const toaster = createToaster({});

// Get Inertia page props
const page = usePage();

// Define table headers for EasyDataTable component
const headers = [
    { text: "ID", value: "id" },
    { text: "Pcs", value: "unit" },
    { text: "Sewing Party", value: "sewing_party.name" },
    { text: "Category", value: "cutting_receive.cutting.category.name" },
    { text: "Available Pcs", value: "available_unit" },
    { text: "Action", value: "action" },
];

//  sewing list
const items = ref(page.props.sewings);

// Search field to filter items
const searchField = ref("name");

// Search input
const searchItem = ref("");
</script>

<template>
    <div>
        <!-- Title -->
        <p class="text-2xl font-bold mb-4">Sewing List</p>

        <!-- Search input -->
        <div
            class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center mb-4"
        >
            <input
                type="text"
                class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
                v-model="searchItem"
                placeholder="Search by name"
            />
        </div>

        <!-- Data table displaying sewing list -->
        <EasyDataTable
            :headers="headers"
            :items="items"
            alternating
            :rows-per-page="5"
            :search-field="searchField"
            :search-value="searchItem"
        >
            <!-- Action button in each row -->
            <template #item-action="{ id }">
                <Link
                    :href="`/sewing-receive-page?sewing_id=${id}`"
                    class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600 transition"
                >
                    Receive Product
                </Link>
            </template>
        </EasyDataTable>
    </div>
</template>
