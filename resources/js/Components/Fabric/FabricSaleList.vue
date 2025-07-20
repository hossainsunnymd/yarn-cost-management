<script setup>
import FabricSaleDetails from "./FabricSaleDetails.vue";
import { usePage, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { ref } from "vue";

// Initialize toaster for notifications
const toaster = createToaster({});

// Access Inertia page props
const page = usePage();

// Modal control & selected fabric product details
const modal = ref(false);
const fabricProducts = ref({});

// Table headers definition for EasyDataTable
const headers = [
    { text: "ID", value: "id" },
    { text: "Customer", value: "customer.name" },
    { text: "Total Cost", value: "total_cost" },
    { text: "Total Sale Price", value: "total_sale_price" },
    { text: "Fabric Sale date", value: "created_at" },
    { text: "Action", value: "action" },
];

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-GB");
};

// Data items to display in the table
const items = ref(page.props.fabricSaleList);
console.log(items.value);

// Search configuration for filtering table data
const searchField = ref(["id", "name", "category.name", "parts_no"]);
const searchItem = ref("");

// Show toaster notification based on flash message status
if (page.props.flash.status === true) {
    toaster.success(page.props.flash.message);
} else if (page.props.flash.status === false) {
    toaster.error(page.props.flash.message);
}

// Open modal and load fabric product details by ID
function showModal(id) {
    fabricProducts.value = items.value.find((item) => item.id == id);
    modal.value = true;
}
</script>

<template>
    <!-- Fabric sale details modal -->
    <FabricSaleDetails v-model:modal="modal" :fabricProducts="fabricProducts" />

    <div class="container mx-auto p-4 bg-white">
        <h1 class="text-2xl font-bold mb-4">Fabric Sale List</h1>

        <!-- Search input -->
        <input
            type="text"
            class="border border-gray-300 rounded-md px-4 py-2 w-[300px]"
            v-model="searchItem"
            placeholder="Search here"
        />

        <!-- Data Table -->
        <EasyDataTable
            :headers="headers"
            :items="items"
            alternating
            :rows-per-page="50"
            :search-field="searchField"
            :search-value="searchItem"
        >
            <!-- Optional image column template -->
            <template #item-image="{ image }">
                <div class="py-2">
                    <img
                        v-if="image"
                        :src="`/uploads/${image}`"
                        :alt="image"
                        class="object-cover h-[50px] w-[50px]"
                    />
                </div>
            </template>

            <!-- Action buttons column -->
            <template #item-action="{ id }">
                <div class="flex gap-1">
                    <button
                        @click="showModal(id)"
                        class="border border-gray-700 text-gray-700 text-xs px-2 py-1 rounded hover:bg-gray-200 transition duration-300"
                        title="View Details"
                    >
                        <span class="material-icons text-sm">visibility</span>
                    </button>
                </div>
            </template>

            <!-- Date Format -->
        <template #item-created_at="{ created_at }">
            {{ formatDate(created_at) }}
        </template>

        
        </EasyDataTable>
    </div>
</template>
