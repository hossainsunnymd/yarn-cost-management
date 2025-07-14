<script setup>
import { ref } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({});
const page = usePage();

// Table headers 
const headers = [
  { text: 'ID', value: 'id' },
  { text: 'Unit', value: 'unit' },
  { text: 'Category', value: 'category.name' },
  { text: 'Roll', value: 'roll' },
  { text: 'Available Unit', value: 'available_unit' },
  { text: 'Action', value: 'action' },
];

// cutting list
const items = ref(page.props.cuttings);

// Search related reactive variables
const searchField = ref("name");
const searchItem = ref();

</script>

<template>
  <p class="text-2xl font-bold mb-4">Cutting List</p>

  <!-- Search input -->
  <div class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center mb-4">
    <div class="w-full md:w-auto">
      <input
        type="text"
        v-model="searchItem"
        placeholder="Search by name"
        class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
      />
    </div>
  </div>

  <!-- EasyDataTable displays the cutting list with pagination and search -->
  <EasyDataTable
    :headers="headers"
    :items="items"
    alternating
    :rows-per-page="5"
    :search-field="searchField"
    :search-value="searchItem"
  >
    <!-- Custom template for Action column buttons -->
    <template #item-action="{ id }">
      <Link
        :href="`/cutting-receive-page?cutting_id=${id}`"
        class="bg-blue-500 text-white font-bold py-2 px-4 rounded"
      >
        Receive Cutting
      </Link>
    </template>
  </EasyDataTable>
</template>

