<script setup>
import { ref } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

// Components
import DyeingPartyDetails from "./DyeingPartyDetails.vue";
import DyeingPayment from "./DyeingPayment.vue";

// Setup
const toaster = createToaster({});
const page = usePage();

// State variables
const items = ref(page.props.dyeingPartyList); // Dyeing party list
const dyeingPayment = ref(page.props.dyeingPayment || {}); // Latest payment
const selectedParty = ref([]);
const paymentId = ref(0);
const modal = ref(false);
const paymentModal = ref(false);
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

// Open details modal for a specific dyeing party
function dyeingPartyDetails(id) {
    selectedParty.value = items.value.find((item) => item.id === id);
    modal.value = true;
}

// Open payment modal for a specific dyeing party
function openPaymentModal(id) {
    paymentId.value = id;
    paymentModal.value = true;
}

// Display toast message if any flash message exists
if (page.props.flash.status === true) {
    toaster.success(page.props.flash.message);
} else if (page.props.flash.status === false) {
    toaster.error(page.props.flash.message);
}
</script>

<template>
    <!-- Modals -->
    <DyeingPartyDetails
        :selectedParty="selectedParty"
        v-model:modal="modal"
        :dyeingPayment="dyeingPayment"
    />
    <DyeingPayment v-model:paymentModal="paymentModal" :paymentId="paymentId" />

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
                :href="`/dyeing-party-save-page?dyeing_party_id=${id}`"
                class="bg-blue-500 text-white font-bold py-2 px-4 rounded"
            >
                Edit
            </Link>

            <button
                @click="deleteDyeingParty(id)"
                class="bg-red-500 text-white font-bold py-2 px-4 rounded ml-1"
            >
                Delete
            </button>

            <button
                @click="dyeingPartyDetails(id)"
                class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-1"
            >
                Details
            </button>

            <button
                @click="openPaymentModal(id)"
                class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-1"
            >
                Payment
            </button>
        </template>
    </EasyDataTable>
</template>
