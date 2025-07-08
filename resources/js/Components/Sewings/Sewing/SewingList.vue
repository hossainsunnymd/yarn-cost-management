<script setup>
import { ref } from 'vue'
import {router, usePage,useForm,Link} from '@inertiajs/vue3'
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({ });
const page=usePage()

const headers = [
  { text: 'ID', value: 'id' },
  { text: 'Pcs', value: 'unit' },
  { text: 'Sewing Party', value: 'sewing_party.name' },
  { text: 'Category', value: 'cutting_receive.cutting.category.name' },
  { text: 'Available Pcs', value: 'available_unit' },
  { text: 'Action', value: 'action' },
];

const items=ref(page.props.sewings);

const searchField = ref("name");
const searchItem=ref();

</script>

<template>
    <p class="text-2xl font-bold">Sewing List</p>
<div class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center">
    <div class="w-full md:w-auto">
        <input
            type="text"
            class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
            v-model="searchItem"
            placeholder="Search by name"
        >
    </div>

</div>

<EasyDataTable :headers="headers" :items="items" alternating :rows-per-page="5" :search-field="searchField" :search-value="searchItem">
    <template #item-action="{ id }">
       <Link :href="`/sewing-receive-page?sewing_id=${id}`" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">Receive Product</Link>
    </template>
</EasyDataTable>
</template>

<style scoped>

</style>
