<script setup>
import FabricSaleDetails from "./FabricSaleDetails.vue";
import { usePage, router, Link } from "@inertiajs/vue3";
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
    { text: "Challan No", value: "challan_no" },
    { text: "Customer", value: "customer.name" },
    { text: "Total Unit", value: "total_unit" },
    { text: "Total Amount", value: "total_amount" },
    { text: "Fabric Sale date", value: "sale_date" },
    { text: "Profit/Loss", value: "profit_loss" },
    { text: "Action", value: "action" },
];

// Data items to display in the table
const items = ref(page.props.fabricSaleList);

// Search configuration for filtering table data
const searchField = ref(["id", "name", "category.name", "parts_no"]);
const searchItem = ref("");

const deleteFabricSale = (id) => {
    if (confirm("Are you sure you want to delete this fabric sale?")) {
        router.get(`/delete-fabric-sale/${id}`);
    }
};

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

                    <button
                        @click="deleteFabricSale(id)"
                        class="bg-red-500 text-white font-bold py-2 px-4 rounded ml-1 hover:bg-red-600 transition duration-300"
                    >
                        Delete
                    </button>
                </div>
            </template>

            <template #item-profit_loss="{ total_amount, total_cost }">
                {{ Number(total_amount - total_cost) }}
            </template>
        </EasyDataTable>
    </div>
</template>
