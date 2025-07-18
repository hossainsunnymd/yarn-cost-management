<script setup>
import { ref } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";


// Setup
const toaster = createToaster({});
const page = usePage();

// State variables
const items = ref(page.props.dyeingPartyList);
const searchItem = ref();
const searchField = ref("name");

// Table headers
const headers = [
    { text: "ID", value: "id" },
    { text: "Name", value: "name" },
    { text: "Phone", value: "phone" },
    { text: "Address", value: "address" },
    { text: "Action", value: "action" },
];

// Delete dyeing party by ID with confirmation
function deleteDyeingParty(id) {
    if (confirm("Are you sure you want to delete this dyeing party?")) {
        router.visit(`/dyeing-party-delete?dyeing_party_id=${id}`);
    }
}

// Display toast message if any flash message exists
if (page.props.flash.status === true) {
    toaster.success(page.props.flash.message);
} else if (page.props.flash.status === false) {
    toaster.error(page.props.flash.message);
}
</script>

<template>

    <!-- Title -->
    <p class="text-2xl font-bold mb-4">Dyeing Party List</p>

    <!-- Top Controls -->
    <div
        class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center mb-4"
    >
        <!-- Search Input -->
        <div class="w-full md:w-auto">
            <input
                type="text"
                v-model="searchItem"
                placeholder="Search by name"
                class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
            />
        </div>

        <!-- Add Button -->
        <div class="w-full md:w-auto">
            <Link
                :href="`/dyeing-party-save-page?dyeing_party_id=0`"
                class="bg-green-500 text-white py-2 px-4 rounded block text-center md:inline-block w-full md:w-auto"
            >
                Add Dyeing Party
            </Link>
        </div>
    </div>

    <!-- Dyeing Party Table -->
    <EasyDataTable
        :headers="headers"
        :items="items"
        alternating
        :rows-per-page="5"
        :search-field="searchField"
        :search-value="searchItem"
    >
        <!-- Action Buttons -->
        <template #item-action="{ id }">
            <Link
                v-if="
                    page.props.user.can['dyeing-save-page'] &&
                    page.props.user.can['update-dyeing-party']
                "
                :href="`/dyeing-party-save-page?dyeing_party_id=${id}`"
                class="bg-blue-500 text-white font-bold py-2 px-4 rounded"
            >
                Edit
            </Link>

            <button
                v-if="page.props.user.can['dyeing-party-delete']"
                @click="deleteDyeingParty(id)"
                class="bg-red-500 text-white font-bold py-2 px-4 rounded ml-1"
            >
                Delete
            </button>

            <Link
                :href="`/dyeing-party-detail-list?dyeing_party_id=${id}`"
                class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-1"
                >Go to Details</Link
            >

            <Link
                :href="`/dyeing-payment-list?dyeing_party_id=${id}`"
                class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-1"
            >
                Payment History
            </Link>
        </template>
    </EasyDataTable>
</template>
