<script setup>
import { ref } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import CuttingPayment from "./CuttingPayment.vue";

// Initialize toaster and page data
const toaster = createToaster({});
const page = usePage();
const paymentModal = ref(false);
const paymentId = ref(
    new URLSearchParams(window.location.search).get("cutting_party_id")
);

// Table headers for EasyDataTable
const headers = [
    { text: "ID", value: "id" },
    { text: "Date", value: "date" },
    { text: "Challan No", value: "challan_no" },
    { text: "Particulars", value: "particulars" },
    { text: "Party Name", value: "cutting_party.name" },
    { text: "Debit", value: "debit" },
    { text: "Credit", value: "credit" },
    { text: "Amount", value: "amount" },
    { text: "Action", value: "action" },
];

// Reactive data
const items = ref(page.props.cuttingPayment);
const searchField = ref("name");
const searchItem = ref("");

// Open payment modal
function openPaymentModal() {
    paymentModal.value = true;
}

function deleteCuttingPayment(id) {
    if (confirm("Are you sure you want to delete this payment?")) {
        router.get(`/cutting-payment-delete/${id}`, {
            onSuccess: () => {
                if (page.props.flash.status === false) {
                    toaster.error(page.props.flash.message);
                } else {
                    toaster.success(page.props.flash.message);
                }
            },
        });
    }
}
</script>

<template>
    <CuttingPayment
        :paymentId="paymentId"
        v-model:paymentModal="paymentModal"
    />
    <!-- Page Title -->
    <p class="text-2xl font-bold">Cutting Payment List</p>

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
                {{ page.props.cuttingPayment[0]?.cutting_party.due_amount }} Tk
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
        <template #item-created_at="{ created_at }">
            {{ new Date(created_at).toLocaleDateString() }}
        </template>
        <template #item-action="{ id }">
            <button
                @click="deleteCuttingPayment(id)"
                class="bg-red-500 px-2 py-1 rounded text-white"
            >
                Delete
            </button>
        </template>
    </EasyDataTable>
</template>
