<script setup>
import { ref } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import InvoiceDetails from "./InvoiceDetails.vue";

const toaster = createToaster();
const page = usePage();
const modal = ref(false);
const products = ref([]);

const headers = [
    { text: "ID", value: "id" },
    { text: "Customer Name", value: "customer.name" },
    { text: "Total Unit", value: "total" },
    { text: "Action", value: "action" },
];

const items = ref(page.props.invoices);
const searchField = ref("name");
const searchItem = ref("");

function deleteVendor(id) {
    if (confirm("Are you sure you want to delete this vendor?")) {
        router.visit(`/delete-customer?customer_id=${id}`);
    }
}

if (page.props.flash.status === true) {
    toaster.success(page.props.flash.message);
} else if (page.props.flash.status === false) {
    toaster.error(page.props.flash.message);
}

function showInvoiceDetailsModal(id) {
    products.value = items.value.find((item) => item.id == id);
    modal.value = true;
    console.log(products.value);
}
</script>

<template>
    <InvoiceDetails v-model:modal="modal" :products="products" />
    <div class="p-6 bg-gray-50 min-h-screen">
        <h1 class="text-3xl font-bold mb-6">Invoices</h1>

        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4"
        >
            <input
                type="text"
                v-model="searchItem"
                placeholder="Search by name"
                class="w-full md:w-72 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
        </div>

        <EasyDataTable
            :headers="headers"
            :items="items"
            alternating
            :rows-per-page="50"
            :search-field="searchField"
            :search-value="searchItem"
            class="shadow-md rounded-lg bg-white"
        >
            <template #item-action="{ id }">
                <div class="flex space-x-2">
                    <button
                        @click="showInvoiceDetailsModal(id)"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md transition"
                    >
                        view
                    </button>
                    <button
                        @click="deleteVendor(id)"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md transition"
                    >
                        Delete
                    </button>
                </div>
            </template>
        </EasyDataTable>
    </div>
</template>
