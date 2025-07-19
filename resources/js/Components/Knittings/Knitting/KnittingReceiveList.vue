<script setup>
import { ref } from 'vue';
import { router, usePage, Link } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";

// Initialize toaster and access page props
const toaster = createToaster();
const page = usePage();

// Define table headers
const headers = [
  { text: 'ID', value: 'id' },
  { text: 'Fabric Name', value: 'knitting.fabric_name' },
  { text: 'Unit', value: 'unit' },
  { text: 'Knitting Cost', value: 'knitting_cost' },
  { text: 'Per Unit Cost', value: 'per_unit_cost' },
  { text: 'Roll', value: 'roll' },
  { text: 'Total Cost', value: 'total_cost' },
  { text: 'Available Unit', value: 'available_unit' },
  { text: 'Action', value: 'action' },
];

// Table data from props
const items = ref(page.props.knittingReceiveList);

// Search functionality
const searchField = ref("name");
const searchItem = ref("");


// Show flash messages if any
if (page.props.flash.status === true) {
  toaster.success(page.props.flash.message);
} else if (page.props.flash.status === false) {
  toaster.error(page.props.flash.message);
}
</script>

<template>
  <!-- Page Title -->
  <p class="text-2xl font-bold mb-4">Knitting Receive List</p>

  <!-- Search Bar -->
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

  <!-- Data Table -->
  <EasyDataTable
    :headers="headers"
    :items="items"
    :rows-per-page="5"
    :search-field="searchField"
    :search-value="searchItem"
    alternating
  >
    <!-- Action Buttons for Each Row -->
    <template #item-action="{ id }">
      <Link v-if="page.props.user.can['dyeing-save-page']"
        :href="`/dyeing-save-page?knitting_receive_id=${id}`"
        class="bg-blue-500 text-white font-bold py-2 px-4 rounded"
      >
        Dyeing
      </Link>
      <Link v-if="page.props.user.can['knitting-sale-page']"
        :href="`/knitting-sale-page?knitting_receive_id=${id}`"
        class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-1"
      >
        Knitting Sale
      </Link>
    </template>
  </EasyDataTable>
</template>


<style scoped>

</style>
