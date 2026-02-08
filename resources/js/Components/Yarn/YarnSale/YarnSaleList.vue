<script setup>
import { ref } from "vue";
import { usePage,router} from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import YarnSaleDetails from "./YarnSaleDetails.vue";

const toaster = createToaster({});
const page = usePage();

// Table headers definition for EasyDataTable component
const headers = [
    { text: "No", value: "id" },
    { text: "Sale Date", value: "sale_date" },
    { text: "Challan No", value: "challan_no" },
    { text: "Unit", value: "total_unit" },
    { text: "Total Amount", value: "total_amount" },
    { text: "Total Cost", value: "total_cost" },
    { text: "Profit/Loss", value: "profit_loss" },
    { text: "Action", value: "action" },
];

// Yarn Sale list
const items = ref(page.props.yarnSaleList);
const yarns=ref();
const modal = ref(false);

// Search functionality
const searchField = ref("name");
const searchItem = ref("");

const showModal=(id)=>{
    yarns.value=items.value.find((yarn) => yarn.id === id);
    modal.value = true;
}

const deleteYarnSale = (id) => {
    if (confirm("Are you sure you want to delete this yarn sale?")) {
        router.visit(`/yarn-sale-delete/${id}`);
    }
}

if(page.props.flash.status === true){
    toaster.success(page.props.flash.message);
}else if(page.props.flash.status === false){
    toaster.error(page.props.flash.message);
}
</script>

<template>
    <YarnSaleDetails :yarns="yarns" :modal="modal" @update:modal="modal = $event" />
    <p class="text-2xl font-bold mb-4">Yarn Sale List</p>

    <!-- Search input -->
    <div
        class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center mb-4"
    >
        <div class="w-full md:w-auto">
            <input
                type="text"
                class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
                v-model="searchItem"
                placeholder="Search by name"
            />
        </div>
    </div>

    <!-- Data table component to display yarn sale items -->
    <EasyDataTable
        :headers="headers"
        :items="items"
        alternating
        :rows-per-page="5"
        :search-field="searchField"
        :search-value="searchItem"
    >

        <template #item-sale_date="{ sale_date }">
            {{ new Date(sale_date).toLocaleDateString('en-GB') }}
        </template>
        <template #item-action="{ id }">
            <div class="flex gap-2">
                <button
                    @click="deleteYarnSale(id)"
                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-4 rounded-md transition"
                >
                    Delete
                </button>
                <button
                    @click="showModal(id)"
                    type="button"
                    class="border border-gray-700 text-gray-700 font-bold py-1 px-2 rounded-md transition text-sm"
                >
                    <span class="material-icons">visibility</span>
                </button>
            </div>
        </template>
       <template #item-profit_loss="{total_amount,total_cost}">
        {{ Number(total_amount - total_cost) }}
       </template>
    </EasyDataTable>
</template>
