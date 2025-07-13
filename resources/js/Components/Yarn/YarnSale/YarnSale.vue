<script setup>
import { usePage, useForm, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { computed } from "vue";

const page = usePage();
const toaster = createToaster({});

// handle validation errors
const errors = computed(() => page.props.flash.error || {});

// Get yarn purchase ID from URL query params
const yarnPurchaseId = new URLSearchParams(window.location.search).get(
    "yarn_purchase_id"
);

// Define the form with initial values
const form = useForm({
    yarn_purchase_id: yarnPurchaseId,
    unit: "",
    total_amount: "",
});

// Submit the form data to backend to create a yarn sale
function submitForm() {
    form.post("/create-yarn-sale", {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status === false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status === true) {
                toaster.success(page.props.flash.message);
                router.visit("/yarn-purchase-list");
            }
        },
    });
}
</script>

<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <!-- Title -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            Add Yarn Sale
        </h2>

        <form @submit.prevent="submitForm" class="space-y-5">
            <!-- Unit Input -->
            <div>
                <label
                    for="unit"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Unit
                </label>
                <input
                    v-model="form.unit"
                    type="text"
                    id="unit"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.unit" class="text-red-500 text-md mt-1">
                    {{ errors.unit[0] }}
                </p>
            </div>

            <!-- Amount Input -->
            <div>
                <label
                    for="total_amount"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Amount
                </label>
                <input
                    v-model="form.total_amount"
                    type="text"
                    id="total_amount"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.total_amount" class="text-red-500 text-md mt-1">
                    {{ errors.total_amount[0] }}
                </p>
            </div>

            <!-- Submit Button -->
            <div class="pt-3">
                <button
                    type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition duration-300"
                >
                    Add Yarn Sale
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped></style>
