<script setup>
import { ref } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

// Child Components
import SewingPartyDetails from "./SewingPartyDetails.vue";
import SewingPayment from "./SewingPayment.vue";

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
const items = ref(page.props.sewingParties);
const sewingPayment = ref(page.props.sewingPayment || {});
const searchField = ref("name");
const searchItem = ref("");
const selectedParty = ref([]);

// Modal states
const modal = ref(false);
const paymentModal = ref(false);
const paymentId = ref(0);

// Delete action with confirmation
function deleteSewingParty(id) {
    if (confirm("Are you sure you want to delete this sewing party?")) {
        router.visit(`/sewing-party-delete?sewing_party_id=${id}`);
    }
}

// Show details modal (currently opens without specific data filtering)
function sewingPartyDetails(id) {
    selectedParty.value = items.value.find((item) => item.id === id);
    console.log(selectedParty.value);   
    modal.value = true;
}

// Open payment modal with selected party ID
function openPaymentModal(id) {
    paymentId.value = id;
    paymentModal.value = true;
}

// Flash message toast
if (page.props.flash.status === true) {
    toaster.success(page.props.flash.message);
} else if (page.props.flash.status === false) {
    toaster.error(page.props.flash.message);
}
</script>

<template>
    <!-- Modals -->
    <SewingPartyDetails
        :selectedParty="selectedParty"
        v-model:modal="modal"
        :sewingPayment="sewingPayment"
    />
    <SewingPayment v-model:paymentModal="paymentModal" :paymentId="paymentId" />

    <!-- Header -->
    <p class="text-2xl font-bold mb-4">Sewing Party List</p>

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
            :href="`/sewing-party-save-page?sewing_party_id=0`"
            class="bg-green-500 text-white py-2 px-4 rounded block text-center md:inline-block w-full md:w-auto"
        >
            Add Sewing Party
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
                    :href="`/sewing-party-save-page?sewing_party_id=${id}`"
                    class="bg-blue-500 text-white font-bold py-1 px-3 rounded text-xs"
                >
                    Edit
                </Link>
                <button
                    @click="deleteSewingParty(id)"
                    class="bg-red-500 text-white font-bold py-1 px-3 rounded text-xs"
                >
                    Delete
                </button>
                <button
                    @click="sewingPartyDetails(id)"
                    class="bg-blue-600 text-white font-bold py-1 px-3 rounded text-xs"
                >
                    Details
                </button>
                <button
                    @click="openPaymentModal(id)"
                    class="bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs"
                >
                    Payment
                </button>
            </div>
        </template>
    </EasyDataTable>
</template>

