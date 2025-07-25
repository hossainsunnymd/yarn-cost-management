<script setup>
import { ref } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";


// Initialize toaster
const toaster = createToaster();
const page = usePage();

// Table Headers
const headers = [
    { text: "ID", value: "id" },
    { text: "Name", value: "name" },
    { text: "Phone", value: "phone" },
    { text: "Address", value: "address" },
    { text: "Action", value: "action" },
];

// Reactive state
const items = ref(page.props.cuttingParties);
const searchField = ref("name");
const searchItem = ref("");

// Modal states
const modal = ref(false);

// Delete action with confirmation
function deleteCuttingParty(id) {
    if (confirm("Are you sure you want to delete this cutting party?")) {
        router.visit(`/cutting-party-delete?cutting_party_id=${id}`);
    }
}

// Flash message toast
if (page.props.flash.status === true) {
    toaster.success(page.props.flash.message);
} else if (page.props.flash.status === false) {
    toaster.error(page.props.flash.message);
}
</script>

<template>

    <!-- Header -->
    <p class="text-2xl font-bold mb-4">Cutting Party List</p>

    <!-- Search and Add Button -->
    <div
        class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center mb-4"
    >
        <input
            type="text"
            class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
            v-model="searchItem"
            placeholder="Search by name"
        />
        <Link
            v-if="page.props.user.can['cutting-party-save-page']"
            :href="`/cutting-party-save-page?cutting_party_id=0`"
            class="bg-green-500 text-white py-2 px-4 rounded block text-center md:inline-block w-full md:w-auto"
        >
            Add Cutting Party
        </Link>
    </div>

    <!-- Table -->
    <EasyDataTable
        :headers="headers"
        :items="items"
        :rows-per-page="5"
        :search-field="searchField"
        :search-value="searchItem"
        alternating
    >
        <!-- Action Buttons -->
        <template #item-action="{ id }">
            <div class="flex flex-wrap gap-1">
                <Link
                    v-if="
                        page.props.user.can['cutting-party-save-page'] &&
                        page.props.user.can['update-cutting-party']
                    "
                    :href="`/cutting-party-save-page?cutting_party_id=${id}`"
                    class="bg-blue-500 text-white font-bold py-1 px-3 rounded text-xs"
                >
                    Edit
                </Link>
                <button
                    v-if="page.props.user.can['cutting-party-delete']"
                    @click="deleteCuttingParty(id)"
                    class="bg-red-500 text-white font-bold py-1 px-3 rounded text-xs"
                >
                    Delete
                </button>
                <Link v-if="page.props.user.can['cutting-party-detail-list']"
                    :href="`/cutting-party-detail-list?cutting_party_id=${id}`"
                    class="bg-blue-500 text-white font-bold py-1 px-3 rounded text-xs"
                    >Go To Details</Link
                >
                <Link v-if="page.props.user.can['cutting-payment-list']"
                    :href="`/cutting-payment-list?cutting_party_id=${id}`"
                    class="bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs"
                >
                    Payment History
                </Link>
            </div>
        </template>
    </EasyDataTable>
</template>
