<script setup>
import { ref } from 'vue'
import {router, usePage,Link} from '@inertiajs/vue3'
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({ });
const page=usePage()

const headers = [
  { text: 'No', value: 'id' },
  { text: 'Unit', value: 'unit' },
  { text: 'Price', value: 'total_amount' },
];


const items=ref(page.props.fabricSaleList);
const searchField = ref("name");
const searchItem=ref();


</script>

<template>
    <p class="text-2xl font-bold">Fabric Sale List</p>
<div class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center">
    <div class="w-full md:w-auto">
        <input
            type="text"
            class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
            v-model="searchItem"
            placeholder="Search by name"
        >
    </div>
    <div>
    </div>
</div>

<EasyDataTable :headers="headers" :items="items" alternating :rows-per-page="5" :search-field="searchField" :search-value="searchItem">
</EasyDataTable>
</template>

<style scoped>

</style>
