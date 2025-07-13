<script setup>
import { ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import InvoiceDetails from "./InvoiceDetails.vue";

// Initialize toaster for notifications
const toaster = createToaster();

// Access page props (invoices, flash messages)
const page = usePage();

// Modal visibility state and selected invoice data
const modal = ref(false);
const products = ref([]);

// Table headers for the invoice list
const headers = [
  { text: "ID", value: "id" },
  { text: "Customer Name", value: "customer.name" },
  { text: "Total Unit", value: "total" },
  { text: "Action", value: "action" },
];

// Data source for invoices and search fields
const items = ref(page.props.invoices);
const searchField = ref("name");
const searchItem = ref("");

//delete incoice
function deleteInvoice(id) {
  if (confirm("Are you sure you want to delete this invoice?")) {
    router.visit(`/delete-invoice?invoice_id=${id}`);
  }
}

// Show success or error toaster message based on flash status
if (page.props.flash.status === true) {
  toaster.success(page.props.flash.message);
} else if (page.props.flash.status === false) {
  toaster.error(page.props.flash.message);
}

// Open modal with selected invoice details
function showInvoiceDetailsModal(id) {
  products.value = items.value.find((item) => item.id == id);
  modal.value = true;
}
</script>

<template>
  <!-- Invoice details modal component -->
  <InvoiceDetails v-model:modal="modal" :products="products" />

  <!-- Main container -->
  <div class="p-6 bg-gray-50 min-h-screen">
    <h1 class="text-3xl font-bold mb-6">Invoices</h1>

    <!-- Search input -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
      <input
        type="text"
        v-model="searchItem"
        placeholder="Search by name"
        class="w-full md:w-72 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
      />
    </div>

    <!-- Invoice table with search and pagination -->
    <EasyDataTable
      :headers="headers"
      :items="items"
      alternating
      :rows-per-page="50"
      :search-field="searchField"
      :search-value="searchItem"
      class="shadow-md rounded-lg bg-white"
    >
      <!-- Action buttons for each row -->
      <template #item-action="{ id }">
        <div class="flex space-x-2">
          <button
            @click="showInvoiceDetailsModal(id)"
            class="border border-gray-700 text-gray-700 text-xs px-2 py-1 rounded hover:bg-gray-200 transition duration-300"
            aria-label="View invoice details"
          >
            <span class="material-icons text-sm">visibility</span>
          </button>
          <button
            @click="deleteInvoice(id)"
            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md transition"
            aria-label="Delete invoice"
          >
            Delete
          </button>
        </div>
      </template>
    </EasyDataTable>
  </div>
</template>
