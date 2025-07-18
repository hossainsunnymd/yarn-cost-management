<script setup>
import { ref } from "vue";
import {  usePage } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import DyeingPartyDetails from "./DyeingPartyDetails.vue";

// Initialize toaster and page data
const toaster = createToaster({});
const page = usePage();

// Table headers for EasyDataTable
const headers = [
    { text: "ID", value: "id" },
    { text: "Name", value: "dyeing_party.name" },
    { text: "Weight", value: "unit" },
    { text: "Available Weight", value: "available_unit" },
];

// Reactive data
const items = ref(page.props.dyeings);
const dyeingPayment = ref(page.props.dyeingPayments || {});
const searchField = ref("name");
const searchItem = ref("");
const selectedParty = ref([]);

const modal = ref(false);

// Show  party details in modal
function dyeingPartyDetails() {
    selectedParty.value = items.value;
    modal.value = true;
}

// Show flash messages if present
if (page.props.flash.status === true) {
    toaster.success(page.props.flash.message);
} else if (page.props.flash.status === false) {
    toaster.error(page.props.flash.message);
}
</script>

<template>
    <!-- Details and Payment Modals -->
    <DyeingPartyDetails
        :selectedParty="selectedParty"
        v-model:modal="modal"
        :dyeingPayment="dyeingPayment"
    />

    <!-- Page Title -->
    <p class="text-2xl font-bold">Dyeing Party Detail List</p>

    <!-- Search and Add Buttons -->
    <div
        class="flex flex-col md:flex-row md:justify-between gap-3 md:items-center mb-4"
    >
        <div class="w-full md:w-auto">
            <input
                type="text"
                v-model="searchItem"
                placeholder="Search by name"
                class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-[300px]"
            />
        </div>
        <div class="w-full md:w-auto">
            <button
                @click="dyeingPartyDetails()"
                class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-1"
            >
                Show Details
            </button>
        </div>
    </div>

    <!-- Yarn Party Table -->
    <EasyDataTable
        :headers="headers"
        :items="items"
        alternating
        :rows-per-page="5"
        :search-field="searchField"
        :search-value="searchItem"
    >
    </EasyDataTable>
</template>
