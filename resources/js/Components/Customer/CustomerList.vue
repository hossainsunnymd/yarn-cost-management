<script setup>
import { ref } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

// Initialize toaster for showing notifications
const toaster = createToaster();
const page = usePage();

// Define table headers
const headers = [
    { text: "ID", value: "id" },
    { text: "Name", value: "name" },
    { text: "Phone", value: "phone" },
    { text: "Address", value: "address" },
    { text: "Action", value: "action" },
];

//  list of customers
const items = ref(page.props.customers);

const searchField = ref("name");
const searchItem = ref("");

// Function to delete a customer after confirmation
function deleteCustomer(id) {
    if (confirm("Are you sure you want to delete this vendor?")) {
        router.visit(`/delete-customer?customer_id=${id}`);
    }
}

// Show toaster notifications based on flash message status
if (page.props.flash.status === true) {
    toaster.success(page.props.flash.message);
} else if (page.props.flash.status === false) {
    toaster.error(page.props.flash.message);
}
</script>

<template>
    <div class="p-6 bg-gray-50 min-h-screen">
        <!-- Page Title -->
        <h1 class="text-3xl font-bold mb-6">Customer List</h1>

        <!-- Search input and Add Customer button -->
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4"
        >
            <input
                type="text"
                v-model="searchItem"
                placeholder="Search by name"
                class="w-full md:w-72 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
            />

            <div>
                <Link
                    v-if="page.props.user.can['customer-save-page']"
                    :href="`/customer-save-page?customer_id=${0}`"
                    class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-5 rounded-md transition"
                >
                    Add Customer
                </Link>
            </div>
        </div>

        <!-- Data table listing customers -->
        <EasyDataTable
            :headers="headers"
            :items="items"
            alternating
            :rows-per-page="50"
            :search-field="searchField"
            :search-value="searchItem"
            class="shadow-md rounded-lg bg-white"
        >
            <!-- Actions column template -->
            <template #item-action="{ id }">
                <div class="flex space-x-2">
                    <!-- Edit button -->
                    <Link
                        v-if="
                            page.props.user.can['customer-save-page'] &&
                            page.props.user.can['update-customer']
                        "
                        :href="`/customer-save-page?customer_id=${id}`"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition"
                    >
                        Edit
                    </Link>
                    <Link

                        :href="`/customer-payment-list?customer_id=${id}`"
                        class="bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs flex items-center"
                    >
                        Payment History
                    </Link>

                    <!-- Delete button -->
                    <button
                        v-if="page.props.user.can['delete-customer']"
                        @click="deleteCustomer(id)"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md transition"
                    >
                        Delete
                    </button>
                </div>
            </template>
        </EasyDataTable>
    </div>
</template>
