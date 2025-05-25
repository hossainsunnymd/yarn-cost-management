<script setup>
import { ref } from 'vue'
import {router, usePage,useForm} from '@inertiajs/vue3'
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({ });
const page=usePage()

const headers = [
  { text: 'ID', value: 'id' },
  { text: 'Dyeing Party Name', value: 'drying_party.name' },
  { text: 'Yarns Name', value: 'knitting.yarn_purchase.name' },
  { text: 'unit', value: 'unit' },
  { text: 'Dyeing Cost', value: 'total_amount' },
  { text: 'Action', value: 'action' },
];

const items=ref(page.props.dyeingList);

const searchField = ref("name");
const searchItem=ref();

const form = useForm({
    dyeing_id: '',
    knitting_id: '',
    yarn_purchase_id: '',
    unit: '',

});

function createFabric(id,unit){
    form.dyeing_id=id
    form.knitting_id = items.value.find(item => item.id === id).knitting.id;
    form.yarn_purchase_id = items.value.find(item => item.id === id).knitting.yarn_purchase.id;
    form.unit=unit

    form.post('/create-fabric',{
        onSuccess: () => {
            if(page.props.flash.status==true){
                toaster.success(page.props.flash.message);
                router.visit('/dyeing-list')
            }else if(page.props.flash.status==false){
                toaster.error(page.props.flash.message);

            }
        }
    });

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
    <template #item-action="{ id,unit }">
        <button @click="createFabric(id,unit)" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">Complete</button>
    </template>
</EasyDataTable>
</template>

<style scoped>

</style>
