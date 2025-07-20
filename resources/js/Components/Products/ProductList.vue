<script setup>
import { ref } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

// Initialize toaster for notifications
const toaster = createToaster();

const page = usePage();

// Define table headers for product list
const headers = [
    { text: "ID", value: "id" },
    { text: "Image", value: "image" },
    { text: "Category", value: "sewing.cutting_receive.cutting.category.name" },
    { text: "Per Pcs Cost", value: "per_unit_cost" },
    { text: "Available Pcs", value: "available_unit", sortable: true },
    { text: "Created date", value: "created_at" },
    { text: "Action", value: "action" },
];

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-GB");
};

// Reactive products list from page props
const items = ref(page.props.products);

// Fields to search in
const searchField = ref(["id", "sewing.cutting_receive.cutting.category.name"]);

// User search input
const searchItem = ref("");

// Show success or error message based on flash status
if (page.props.flash.status === true) {
    toaster.success(page.props.flash.message);
} else if (page.props.flash.status === false) {
    toaster.error(page.props.flash.message);
}
</script>

<template>
    <div class="container mx-auto p-4 bg-white">
        <!-- Page title -->
        <h1 class="text-2xl font-bold mb-4">Product List</h1>

        <!-- Search input -->
        <input
            type="text"
            v-model="searchItem"
            placeholder="Search here"
            class="border border-gray-300 rounded-md px-4 py-2 w-[300px] mb-4"
        />

        <!-- Products table -->
        <EasyDataTable
            :headers="headers"
            :items="items"
            alternating
            :rows-per-page="50"
            :search-field="searchField"
            :search-value="searchItem"
        >
            <!-- Image column -->
            <template #item-image="{ image }">
                <div class="py-2">
                    <img
                        v-if="image"
                        :src="`storage/uploads/${image}`"
                        :alt="image"
                        class="object-cover h-[50px] w-[50px]"
                    />
                </div>
            </template>

            <!-- Action column with edit link -->
            <template #item-action="{ id }">
                <div class="flex gap-1">
                    <Link v-if="page.props.user.can['product-update-page']"
                        :href="`/product-update-page?sewing_receive_id=${id}`"
                        class="bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded text-xs flex items-center justify-center transition duration-300"
                        aria-label="Edit product"
                    >
                        <span class="material-icons text-sm">edit</span>
                    </Link>
                </div>
            </template>

            <!-- Date Format -->
            <template #item-created_at="{ created_at }">
                {{ formatDate(created_at) }}
            </template>


        </EasyDataTable>
    </div>
</template>
