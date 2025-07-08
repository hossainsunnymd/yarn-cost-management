<script setup>
import FabricSaleDetails from "./FabricSaleDetails.vue";
import { usePage, Link, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { ref } from "vue";
const toaster = createToaster({});
const page = usePage();
const modal = ref(false);
const fabricProducts = ref({});

const headers = [
    { text: "ID", value: "id" },
    { text: "Customer", value: "customer.name" },
    { text: "Total Cost", value: "total" },
    { text: "Action", value: "action" },
];
const items = ref(page.props.fabricSaleList);

const searchField = ref(["id", "name", "category.name", "parts_no"]);
const searchItem = ref();


if (page.props.flash.status == true) {
    toaster.success(page.props.flash.message);
} else if (page.props.flash.status == false) {
    toaster.error(page.props.flash.message);
}

function showModal(id) {
    fabricProducts.value = items.value.find((item) => item.id == id);
    modal.value = true;
}

</script>

<template>
    <FabricSaleDetails v-model:modal="modal" :fabricProducts="fabricProducts"/>
    <div class="container mx-auto p-4 bg-white">
        <h1 class="flex text-2xl font-bold mb-4">Product List</h1>

        <input
            type="text"
            class="border border-gray-300 rounded-md px-4 py-2 w-[300px]"
            v-model="searchItem"
            placeholder="Search here"
        />

        <div>
            <EasyDataTable
                :headers="headers"
                :items="items"
                alternating
                :rows-per-page="50"
                :search-field="searchField"
                :search-value="searchItem"
            >
                <template #item-image="{ image }">
                    <div class="py-2">
                        <img
                            v-if="image"
                            :src="`/uploads/${image}`"
                            :alt="image"
                            class="object-cover h-[50px] w-[50px]"
                        />
                    </div>
                </template>

                <template #item-action="{ id }">
                    <div class="flex gap-1">
                        <button
                            @click="showModal(id)"
                            class="border border-gray-700 text-gray-700 text-xs px-2 py-1 rounded hover:bg-gray-200 transition duration-300"
                        >
                            <span class="material-icons text-sm">visibility</span>
                        </button>
                    </div>
                </template>
            </EasyDataTable>
        </div>
    </div>
</template>

