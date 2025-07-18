<script setup>
import { ref } from 'vue';
import { router, usePage, Link } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";

// Initialize toaster for flash messages
const toaster = createToaster({});
const page = usePage();

// Table headers for EasyDataTable
const headers = [
  { text: 'ID', value: 'id' },
  { text: 'Unit', value: 'unit' },
  { text: 'Cutting Cost', value: 'cutting_cost' },
  { text: 'Category', value: 'cutting.category.name' }, // Nested relation
  { text: 'Per Unit Cost', value: 'per_unit_cost' },
  { text: 'Total Cost', value: 'total_cost' },
  { text: 'Available Pcs', value: 'available_unit' },
  { text: 'Action', value: 'action' },
];

// Cutting receive data from backend (passed from controller)
const items = ref(page.props.cuttingReceives);

// Search functionality
const searchField = ref("name");
const searchItem = ref();
</script>

<template>
  <div>
    <p class="text-2xl font-bold mb-4">Cutting Receive List</p>

    <!-- Search Box -->
    <div class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center mb-4">
      <div class="w-full md:w-auto">
        <input
          type="text"
          v-model="searchItem"
          placeholder="Search by name"
          class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
        >
      </div>
    </div>

    <!-- Cutting Receive Table -->
    <EasyDataTable
      :headers="headers"
      :items="items"
      alternating
      :rows-per-page="5"
      :search-field="searchField"
      :search-value="searchItem"
    >
      <!-- Action Column Template -->
      <template #item-action="{ id }">
        <Link v-if="page.props.user.can['sewing-save-page']"
          :href="`/sewing-save-page?cutting_receive_id=${id}`"
          class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600 transition"
        >
          Sewing
        </Link>
      </template>
    </EasyDataTable>
  </div>
</template>

