<script setup>
import { ref } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import YarnPayment from "./YarnPayment.vue";

// Initialize toaster and page data
const toaster = createToaster({});
const page = usePage();
const paymentModal = ref(false);
const paymentId = ref(new URLSearchParams(window.location.search).get("yarn_party_id"));

// Table headers for EasyDataTable
const headers = [
    { text: "ID", value: "id" },
    { text: "Party Name", value: "yarn_party.name" },
    { text: "Amount", value: "amount" },
];

// Reactive data
const items = ref(page.props.yarnPayments);
const searchField = ref("name");
const searchItem = ref("");

// Open payment modal
function openPaymentModal() {
    paymentModal.value = true;
}
</script>

<template>
      <YarnPayment v-model:paymentModal="paymentModal" :paymentId="paymentId" />
    <!-- Page Title -->
    <p class="text-2xl font-bold">Yarn Payment List</p>

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
            <p class="mt-4 font-bold">
                Total Due:
                {{ page.props.yarnPayments[0]?.yarn_party.due_amount }} Tk
            </p>
            <p class="mt-4 font-bold">
                Total Payments: {{ page.props.totalPayment }} Tk
            </p>
        </div>
        <div class="">
            <button
                @click="openPaymentModal()"
                class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-1"
            >
                Payment
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
