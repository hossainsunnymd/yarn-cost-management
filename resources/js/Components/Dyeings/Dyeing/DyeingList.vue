<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
//  data table headers
const headers = [
  { text: 'ID', value: 'id' },
  { text: 'Dyeing Party Name', value: 'dyeing_party.name' },
  { text: 'Design Name', value: 'design_name' },
  { text: 'Unit', value: 'unit' },
  { text: 'Available Unit', value: 'available_unit' },
  { text: 'Roll', value: 'roll' },
  { text: 'Action', value: 'action' },
];

//  dyeing list
const items = ref(page.props.dyeingList);

// Define search parameters
const searchField = ref("name");
const searchItem = ref();
</script>

<template>
  <!-- Page Title -->
  <p class="text-2xl font-bold">Dyeing List</p>

  <!-- Search Bar -->
  <div class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center my-4">
    <div class="w-full md:w-auto">
      <input
        type="text"
        class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
        v-model="searchItem"
        placeholder="Search by name"
      />
    </div>
  </div>

  <!-- Data Table -->
  <EasyDataTable
    :headers="headers"
    :items="items"
    alternating
    :rows-per-page="5"
    :search-field="searchField"
    :search-value="searchItem"
  >
    <!-- Action Button Column -->
    <template #item-action="{ id }">
      <Link
        :href="`/dyeing-receive-page?dyeing_id=${id}`"
        class="bg-blue-500 text-white font-bold py-2 px-4 rounded"
      >
        Dyeing Receive
      </Link>
    </template>
  </EasyDataTable>
</template>


