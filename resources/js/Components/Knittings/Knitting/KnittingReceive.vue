<script setup>
// Imports
import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router } from "@inertiajs/vue3";
import { computed } from "vue";

// Access Inertia page props
const page = usePage();
const toaster = createToaster();

// Get knitting_id from query string
const knittingId = new URLSearchParams(window.location.search).get(
    "knitting_id"
);

// Form state
const form = useForm({
    knitting_id: knittingId,
    unit: "",
    wastage: "",
    roll: "",
    knitting_cost: "",
});

// Display validation errors
const errors = computed(() => page.props.flash.error || {});

// Submit handler
const URL = "/create-knitting-receive";

function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status === false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status === true) {
                toaster.success(page.props.flash.message);
                router.visit("/knitting-list");
            }
        },
    });
}
</script>

<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            Knitting Receive
        </h2>

        <form @submit.prevent="submitForm" class="space-y-5">
            <!-- Unit Field -->
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
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.unit" class="text-red-500 text-md mt-1">
                    {{ errors.unit[0] }}
                </p>
            </div>

            <!-- Knitting Cost Field -->
            <div>
                <label
                    for="knitting_cost"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Knitting Cost
                </label>
                <input
                    v-model="form.knitting_cost"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p
                    v-if="errors.knitting_cost"
                    class="text-red-500 text-md mt-1"
                >
                    {{ errors.knitting_cost[0] }}
                </p>
            </div>

            <!-- Roll Field -->
            <div>
                <label
                    for="roll"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Roll
                </label>
                <input
                    v-model="form.roll"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.roll" class="text-red-500 text-md mt-1">
                    {{ errors.roll[0] }}
                </p>
            </div>

            <!-- Wastage Field -->
            <div>
                <label
                    for="wastage"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Wastage
                </label>
                <input
                    v-model="form.wastage"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.wastage" class="text-red-500 text-md mt-1">
                    {{ errors.wastage[0] }}
                </p>
            </div>

            <!-- Submit Button -->
            <div class="pt-3">
                <button
                    type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition duration-300"
                >
                    Confirm
                </button>
            </div>
        </form>
    </div>
</template>
