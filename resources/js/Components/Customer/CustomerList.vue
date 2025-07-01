<script setup>
import { ref } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster();
const page = usePage();

const headers = [
    { text: 'ID', value: 'id' },
    { text: 'Name', value: 'name' },
    { text: 'Phone', value: 'phone' },
    { text: 'Address', value: 'address' },
    { text: 'Action', value: 'action' },
];

const items = ref(page.props.customers);
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
</script>

<template>
  <div class="p-6 bg-gray-50 min-h-screen">
    <h1 class="text-3xl font-bold mb-6">Customer List</h1>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
      <input
        type="text"
        v-model="searchItem"
        placeholder="Search by name"
        class="w-full md:w-72 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
      />

      <div>
        <Link
          :href="`/customer-save-page?customer_id=${0}`"
          class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-5 rounded-md transition"
        >
          Add Customer
        </Link>
      </div>
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
          <Link
            :href="`/customer-save-page?customer_id=${id}`"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition"
          >
            Edit
          </Link>
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

