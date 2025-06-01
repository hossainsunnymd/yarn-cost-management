<script setup>
import { ref } from 'vue'
import {router, usePage,Link} from '@inertiajs/vue3'
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({ });
const page=usePage()

const headers = [
  { text: 'No', value: 'id' },
  { text: 'unit', value: 'unit' },
  { text: 'Per Unit Cost', value: 'per_unit_cost' },
  { text: 'Total Cost', value: 'total_amount' },
  { text: 'Action', value: 'action' },
];

const items=ref(page.props.fabrics);
console.log(items.value);

const searchField = ref("name");
const searchItem=ref();


if(page.props.flash.status==true){
    toaster.success(page.props.flash.message);
}else if(page.props.flash.status==false){
    toaster.error(page.props.flash.message);

}


</script>

<template>
    <p class="text-2xl font-bold">Dyeing List</p>
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
        <!-- <Link :href="`/drying-save-page?id=${id}`" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">Dyeing</Link> -->
    </template>
</EasyDataTable>
</template>

<style scoped>

</style>
