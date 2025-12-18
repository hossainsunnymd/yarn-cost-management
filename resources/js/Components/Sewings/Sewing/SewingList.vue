<script setup>
import { ref } from "vue";
import { usePage, Link,router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

// Initialize toaster for notifications
const toaster = createToaster({});

// Get Inertia page props
const page = usePage();

// Define table headers for EasyDataTable component
const headers = [
    { text: "ID", value: "id" },
    { text: "Challan No", value: "challan_no" },
    { text: "Pcs", value: "unit" },
    { text: "Sewing Party", value: "sewing_party.name" },
    { text: "Category", value: "cutting_receive.cutting.category.name" },
    { text: "Available Pcs", value: "available_unit" },
    { text: "Created date", value: "created_at" },
    { text: "Action", value: "action" },
];

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-GB");
};

//  sewing list
const items = ref(page.props.sewings);


// Search field to filter items
const searchField = ref("name");

// Search input
const searchItem = ref("");

const deleteSewing = (id) => {
    if (confirm("Are you sure you want to delete this sewing?")) {
        router.get(`/sewing-delete?sewing_id=${id}`);
    }
}

if(page.props.flash.status === true){
    toaster.success(page.props.flash.message);
}else if(page.props.flash.status === false){
    toaster.error(page.props.flash.message);

}
</script>

<template>
    <div>
        <!-- Title -->
        <p class="text-2xl font-bold mb-4">Sewing List</p>

        <!-- Search input -->
        <div
            class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center mb-4"
        >
            <input
                type="text"
                class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
                v-model="searchItem"
                placeholder="Search by name"
            />
        </div>

        <!-- Data table displaying sewing list -->
        <EasyDataTable
            :headers="headers"
            :items="items"
            alternating
            :rows-per-page="5"
            :search-field="searchField"
            :search-value="searchItem"
        >
            <!-- Action button in each row -->
            <template #item-action="{ id }">
                <Link v-if="page.props.user.can['sewing-receive-page']"
                    :href="`/sewing-receive-page?sewing_id=${id}`"
                    class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600 transition"
                >
                    Receive Product
                </Link>
                <button @click="deleteSewing(id)" class="bg-red-500 text-white font-bold py-2 px-4 rounded hover:bg-red-600 transition">Delete</button>
            </template>

            <!-- Date Format -->
            <template #item-created_at="{ created_at }">
                {{ formatDate(created_at) }}
            </template>
        </EasyDataTable>
    </div>
</template>
