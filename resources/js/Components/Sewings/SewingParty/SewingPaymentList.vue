<script setup>
import { ref,computed } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import SewingPayment from "./SewingPayment.vue";

// Initialize toaster and page data
const toaster = createToaster({});
const page = usePage();
const paymentModal = ref(false);
const paymentId = ref(
    new URLSearchParams(window.location.search).get("sewing_party_id")
);

// Table headers for EasyDataTable
const headers = [
    { text: "ID", value: "id" },
    { text: "Date", value: "date" },
    { text: "Challan No", value: "challan_no" },
    { text: "Particulars", value: "particulars" },
    { text: "Party Name", value: "sewing_party.name" },
    { text: "Debit", value: "debit" },
    { text: "Credit", value: "credit" },
    { text: "Amount", value: "amount" },
    { text: "Action", value: "action" },
];

// Reactive data
const items = computed(() => page.props.sewingPayment);
const searchField = ref("name");
const searchItem = ref("");

// Open payment modal
function openPaymentModal() {
    paymentModal.value = true;
}

function deleteSewingPayment(id) {
    if (confirm("Are you sure you want to delete this payment?")) {
        router.get(`/sewing-payment-delete/${id}`, {
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
    <SewingPayment :paymentId="paymentId" v-model:paymentModal="paymentModal" />
    <!-- Page Title -->
    <p class="text-2xl font-bold">Sewing Payment List</p>

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
                {{ page.props.sewingParty.due_amount }} Tk
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
            {{ new Date(created_at).toLocaleDateString("en-GB") }}
        </template>
        <template #item-action="{ id }">
            <button
                @click="deleteSewingPayment(id)"
                class="bg-red-500 px-2 py-1 rounded text-white"
            >
                Delete
            </button>
        </template>
    </EasyDataTable>

    <div class="flex justify-end items-center mt-4 space-x-2">
        <!-- Prev -->
        <Link
            v-if="page.props.pagination.prev_page_url"
            :href="page.props.pagination.prev_page_url"
            class="px-3 py-1.5 text-sm rounded border border-gray-300 text-gray-700 hover:bg-gray-100 transition"
        >
            ← Prev
        </Link>

        <!-- Page Info -->
        <span class="text-sm text-gray-500">
            Page {{ page.props.pagination.current_page }} of
            {{ page.props.pagination.last_page }}
        </span>

        <!-- Next -->
        <Link
            v-if="page.props.pagination.next_page_url"
            :href="page.props.pagination.next_page_url"
            class="px-3 py-1.5 text-sm rounded bg-blue-600 text-white hover:bg-blue-700 transition"
        >
            Next →
        </Link>
    </div>
</template>
