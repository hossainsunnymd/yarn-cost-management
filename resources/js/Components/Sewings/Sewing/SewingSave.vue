<script setup>
import { usePage, useForm, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { computed } from "vue";

// Access page props and flash messages
const page = usePage();
const toaster = createToaster();

// Handle validation errors
const errors = computed(() => page.props.flash.error || {});

// Get cutting_receive_id from query params
const cuttingReceiveId = new URLSearchParams(window.location.search).get(
    "cutting_receive_id"
);

// Form setup
const form = useForm({
    cutting_receive_id: cuttingReceiveId,
    sewing_party_id: "",
    unit: "",
});

// Form submission URL
const URL = "/create-sewing";

// Submit form to backend
function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status === false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status === true) {
                toaster.success(page.props.flash.message);
                router.visit("/sewing-list");
            }
        },
    });
}
</script>

<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <!-- Page Title -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            Add Sewing
        </h2>

        <!-- Sewing Form -->
        <form @submit.prevent="submitForm" class="space-y-5">
            <!-- Sewing Party Dropdown -->
            <div>
                <label
                    for="sewing_party_id"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Sewing Party
                </label>
                <select
                    v-model="form.sewing_party_id"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                    <option value="">Select Sewing Party</option>
                    <option
                        v-for="party in page.props.sewingParty"
                        :key="party.id"
                        :value="party.id"
                    >
                        {{ party.name }}
                    </option>
                </select>
                <p
                    v-if="errors.sewing_party_id"
                    class="text-red-500 text-md mt-1"
                >
                    {{ errors.sewing_party_id[0] }}
                </p>
            </div>

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
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.unit" class="text-red-500 text-md mt-1">
                    {{ errors.unit[0] }}
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
