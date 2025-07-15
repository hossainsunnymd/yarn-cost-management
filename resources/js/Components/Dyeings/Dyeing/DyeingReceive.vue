<script setup>
import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router } from "@inertiajs/vue3";
import { computed } from "vue";

// Initialize toaster for success/error notifications
const toaster = createToaster({});

const page = usePage();

// Get dyeing_id from the URL query parameters
const dyeingId = new URLSearchParams(window.location.search).get("dyeing_id");

// Handle validation errors from flash messages
const errors = computed(() => page.props.flash.error || {});

// Initialize form with default values
const form = useForm({
    dyeing_id: dyeingId,
    unit: "",
    wastage: "",
    per_unit_dyeing_cost: "",
    roll: "",
});
//calculote total dyeing cost
const totalDyeingCost = computed(() => {
    return form.per_unit_dyeing_cost * form.unit;
});

let URL = "/create-dyeing-receive";

// Submit the form data
function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status === false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status === true) {
                toaster.success(page.props.flash.message);
                router.visit("/dyeing-list");
            }
        },
    });
}
</script>

<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <!-- Page Title -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            Dyeing Receive
        </h2>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-5">
            <!-- Available Unit Input -->
            <div>
                <label
                    for="available_unit"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Available Unit</label
                >
                <input
                    :value="page.props.dyeing.available_unit"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                    readonly
                />
            </div>

            <!-- Unit Input -->
            <div>
                <label
                    for="unit"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Unit</label
                >
                <input
                    v-model="form.unit"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.unit" class="text-red-500 text-md mt-1">
                    {{ errors.unit[0] }}
                </p>
            </div>

            <!-- Per Unit Dyeing Cost Input -->
            <div>
                <label
                    for="per_unit_dyeing_cost"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Per Unit Dyeing Cost</label
                >
                <input
                    v-model="form.per_unit_dyeing_cost"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p
                    v-if="errors.per_unit_dyeing_cost"
                    class="text-red-500 text-md mt-1"
                >
                    {{ errors.per_unit_dyeing_cost[0] }}
                </p>
            </div>

            <!-- Total Dyeing Cost Input -->
            <div>
                <label
                    for="total_dyeing_cost"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Total Dyeing Cost</label
                >
                <input
                    :value="totalDyeingCost"
                    type="text"
                    readonly
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>

            <!-- Wastage Input -->
            <div>
                <label
                    for="wastage"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Wastage</label
                >
                <input
                    v-model="form.wastage"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <!-- No error shown here -->
            </div>

            <!-- Roll Input -->
            <div>
                <label
                    for="roll"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Roll</label
                >
                <input
                    v-model="form.roll"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.roll" class="text-red-500 text-md mt-1">
                    {{ errors.roll[0] }}
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

<style scoped>
/* You can add custom styles here if needed */
</style>
