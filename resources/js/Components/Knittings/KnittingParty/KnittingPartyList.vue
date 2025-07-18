<script setup>
import { ref } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";


// Toast setup
const toaster = createToaster();
const page = usePage();

// Table headers
const headers = [
    { text: "ID", value: "id" },
    { text: "Name", value: "name" },
    { text: "Phone", value: "phone" },
    { text: "Address", value: "address" },
    { text: "Action", value: "action" },
];

// Data
const items = ref(page.props.knittingPartyList);

// Search functionality
const searchField = ref("name");
const searchItem = ref("");

// Show toaster based on flash messages
if (page.props.flash.status === true) {
    toaster.success(page.props.flash.message);
} else if (page.props.flash.status === false) {
    toaster.error(page.props.flash.message);
}

// Delete Party with confirmation
function deleteKnittingParty(id) {
    if (confirm("Are you sure you want to delete this knitting party?")) {
        router.visit(`/knitting-party-delete?id=${id}`);
    }
}
</script>

<template>

    <!-- Header -->
    <p class="text-2xl font-bold mb-4">Knitting Party List</p>

    <!-- Search & Add Button -->
    <div
        class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center mb-4"
    >
        <!-- Search Box -->
        <input
            type="text"
            v-model="searchItem"
            placeholder="Search by name"
            class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
        />

        <!-- Add Button -->
        <Link
            v-if="page.props.user.can['knitting-party-save-page']"
            :href="`/knitting-party-save-page?knitting_party_id=0`"
            class="bg-green-500 text-white py-2 px-4 rounded text-center block md:inline-block w-full md:w-auto"
        >
            Add Knitting Party
        </Link>
    </div>

    <!-- Data Table -->
    <EasyDataTable
        :headers="headers"
        :items="items"
        :search-field="searchField"
        :search-value="searchItem"
        :rows-per-page="5"
        alternating
    >
        <!-- Action Column -->
        <template #item-action="{ id }">
            <Link
                v-if="
                    page.props.user.can['knitting-party-save-page'] &&
                    page.props.user.can['update-knitting-party']
                "
                :href="`/knitting-party-save-page?knitting_party_id=${id}`"
                class="bg-blue-500 text-white font-bold py-2 px-4 rounded"
            >
                Edit
            </Link>

            <button
                v-if="page.props.user.can['knitting-party-delete']"
                @click="deleteKnittingParty(id)"
                class="bg-red-500 text-white font-bold py-2 px-4 rounded ml-1"
            >
                Delete
            </button>

            <Link
                :href="`/knitting-party-detail-list?knitting_party_id=${id}`"
                class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-1"
                >Go to Details</Link
            >

            <Link
                :href="`/knitting-payment-list?knitting_party_id=${id}`"
                class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-1"
            >
                Payment History
            </Link>
        </template>
    </EasyDataTable>
</template>

<style scoped></style>
