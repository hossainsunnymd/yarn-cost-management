<script setup>
import { ref } from 'vue'
import {router, usePage,Link} from '@inertiajs/vue3'
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({ });
const page=usePage()

const headers = [
  { text: 'ID', value: 'id' },
  { text: 'Unit', value: 'unit' },
  { text: 'Knitting Cost', value: 'knitting_cost' },
  { text: 'Per Unit Cost', value: 'per_unit_cost' },
  { text: 'Total Cost', value: 'total_amount' },
  { text: 'Action', value: 'action' },
];



const items=ref(page.props.knittingReceiveList);

const searchField = ref("name");
const searchItem=ref();


function deleteYarnParty(id){
    if(confirm("Are you sure you want to delete this knitting party?")){
        router.visit(`/knitting-party-delete?id=${id}`);
    }
}

if(page.props.flash.status==true){
    toaster.success(page.props.flash.message);
}else if(page.props.flash.status==false){
    toaster.error(page.props.flash.message);

}


</script>

<template>
    <p class="text-2xl font-bold">Knitting Receive List</p>
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
            :href="`/knitting-party-save-page?knitting_party_id=${0}`"
            class="bg-green-500 text-white py-2 px-4 rounded block text-center md:inline-block w-full md:w-auto"
        >
            Add Knitting Party
        </Link>
    </div>
</div>

<EasyDataTable :headers="headers" :items="items" alternating :rows-per-page="5" :search-field="searchField" :search-value="searchItem">
    <template #item-action="{ id }">
        <Link :href="`/knitting-party-save-page?knitting_party_id=${id}`" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">Edit</Link>
        <button @click="deleteYarnParty(id)" class="bg-red-500 text-white font-bold py-2 px-4 rounded ml-1">Delete</button>
    </template>

</EasyDataTable>
</template>

<style scoped>

</style>
