<script setup>
import { ref } from 'vue'
import {router, usePage,Link} from '@inertiajs/vue3'
import { createToaster } from "@meforma/vue-toaster";
import KnittingPartyDetails from "./KnittingPartyDetails.vue";
import KnittingPayment from "./KnittingPayment.vue";

const toaster = createToaster({ });
const page=usePage()

const headers = [
  { text: 'ID', value: 'id' },
  { text: 'Name', value: 'name' },
  { text: 'Phone', value: 'phone' },
  { text: 'Address', value: 'address' },
  { text: 'Action', value: 'action' },
];


const items=ref(page.props.knittingPartyList);
const searchField = ref("name");
const searchItem=ref();
const modal=ref(false);
const paymentModal=ref(false);
const paymentId=ref(0);

function deleteKnittingParty(id){
    if(confirm("Are you sure you want to delete this knitting party?")){
        router.visit(`/knitting-party-delete?id=${id}`);
    }
}

if(page.props.flash.status==true){
    toaster.success(page.props.flash.message);
}else if(page.props.flash.status==false){
    toaster.error(page.props.flash.message);

}

function knittingPartyDetails(id){
    items.value.find((item)=>item.id==id);
    modal.value=!modal.value;

}

function openPaymentModal(id){
    paymentId.value=id
    paymentModal.value=!paymentModal.value
}


</script>

<template>
    <KnittingPartyDetails :items="items" v-model:modal="modal"/>
    <KnittingPayment :paymentId="paymentId" v-model:paymentModal="paymentModal"/>
    <p class="text-2xl font-bold">Knitting Party List</p>
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
        <button @click="deleteKnittingParty(id)" class="bg-red-500 text-white font-bold py-2 px-4 rounded ml-1 cursor-pointer">Delete</button>
        <button @click="knittingPartyDetails(id)" class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-1 cursor-pointer">Details</button>
        <button @click="openPaymentModal(id)" class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-1 cursor-pointer">Payment</button>
    </template>

</EasyDataTable>
</template>

<style scoped>

</style>
