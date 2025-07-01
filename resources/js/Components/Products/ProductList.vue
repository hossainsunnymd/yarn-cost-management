<script setup>
import { usePage, Link, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import axios from "axios";
import { ref, onMounted } from "vue";
const toaster = createToaster({});
const page = usePage();


const headers = [
    { text: "ID", value: "id" },
    { text: "Image", value: "image" },
    { text: "Product Name", value: "name" },
    { text: "Category", value: "category.name" },
    { text: "Per Unit Cost", value: "per_unit_cost" },
    { text: "Total ", value: "parts_no" },
    { text: "Action", value: "action" },
];
const items = ref(page.props.products);
console.log(items.value);

const searchField = ref(["id", "name", "category.name", "parts_no"]);
const searchItem = ref();

function deleteProduct(porduct_id) {
    if (confirm("Are you sure you want to delete this product?")) {
        router.visit(`/delete-product?product_id=${porduct_id}`);
    }
}

if (page.props.flash.status == true) {
    toaster.success(page.props.flash.message);
} else if (page.props.flash.status == false) {
    toaster.error(page.props.flash.message);
}
</script>

<template>

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
                            :src="`/uploads/${image}`"
                            :alt="image"
                            class="object-cover h-[50px] w-[50px]"
                        />
                    </div>
                </template>

                <template #item-action="{ id }">
                    <div class="flex gap-1">
                        <Link

                            :href="`/product-save-page?product_id=${id}`"
                            class="bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded text-xs flex items-center justify-center transition duration-300"
                        >
                            <span class="material-icons text-sm">edit</span>
                        </Link>
                        <button

                            @click="deleteProduct(id)"
                            class="bg-red-500 hover:bg-red-600 text-white py-1 px-2 rounded text-xs flex items-center justify-center transition duration-300"
                        >
                            <span class="material-icons text-sm">delete</span>
                        </button>


                    </div>
                </template>
            </EasyDataTable>
        </div>
    </div>
</template>
