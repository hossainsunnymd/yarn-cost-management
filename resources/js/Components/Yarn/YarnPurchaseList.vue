<script setup>
import { ref } from 'vue'
import {router, usePage,Link} from '@inertiajs/vue3'
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({ });
const page=usePage()

const headers = [
  { text: 'ID', value: 'id' },
  { text: 'Name', value: 'name' },
  { text: 'Description', value: 'description' },
  { text: 'Weight', value: 'unit' },
  { text: 'Available Weight', value: 'weight' },
  { text: 'Bag', value: 'bags' },
  { text: 'Yarn Rate', value: 'yarn_rate' },
  { text: 'Bill Amount', value: 'bill_amount' },
  { text: 'Labour Cost', value: 'labour_cost' },
  { text: 'Total Amount', value: 'total_amount' },
  { text: 'Per Unit Cost', value: 'per_unit_cost' },
  { text: 'Current Total Amount', value: 'current_total_amount' },
  { text: 'Action', value: 'action' },
];


const items=ref(page.props.yarnPurchaseList);

const searchField = ref("name");
const searchItem=ref();

function deleteYarnPurchase(id){
    if(confirm("Are you sure you want to delete this yarn party?")){
        router.visit(`/yarn-purchase-delete?id=${id}`);
    }
}

if(page.props.flash.status==true){
    toaster.success(page.props.flash.message);
}else if(page.props.flash.status==false){
    toaster.error(page.props.flash.message);

}


</script>

<template>
    <p class="text-2xl font-bold">Yarn Purchase List</p>
<div class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center">
    <div class="w-full md:w-auto">
        <input
            type="text"
            class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
            v-model="searchItem"
            placeholder="Search by name"
        >
    </div>
    <div class="w-full md:w-auto">
        <Link
            :href="`/yarn-purchase-save-page?id=${0}`"
            class="bg-green-500 text-white py-2 px-4 rounded block text-center md:inline-block w-full md:w-auto"
        >
            Add Yarn Purchase
        </Link>
    </div>
</div>

<EasyDataTable :headers="headers" :items="items" alternating :rows-per-page="5" :search-field="searchField" :search-value="searchItem">
    <template #item-action="{ id }">
        <Link :href="`/yarn-purchase-save-page?id=${id}`" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">Edit</Link>
        <button @click="deleteYarnPurchase(id)" class="bg-red-500 text-white font-bold py-2 px-4 rounded m-1">Delete</button>
        <Link :href="`/knitting-save-page?yarn_purchase_id=${id}`" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">Knitting</Link>
        <Link :href="`/yarn-sale-page?yarn_purchase_id=${id}`" class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-1">Yarn Sale</Link>
    </template>

</EasyDataTable>
</template>

<style scoped>

</style>

