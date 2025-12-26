<script setup>
// Import required modules from Vue and Inertia
import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router } from "@inertiajs/vue3";
import { computed } from "vue";

import vSelect from "vue3-select";
import "vue3-select/dist/vue3-select.css";

const page = usePage();

// Get knitting_receive_id from URL query string
const knittingReceiveId = new URLSearchParams(window.location.search).get(
    "knitting_receive_id"
);

// Handle validation errors from flash messages
const errors = computed(() => page.props.flash.error || {});

// Initialize toaster for notification popups
const toaster = createToaster({});

// Initialize form with default values
const form = useForm({
    challan_no: "",
    dyeing_party_id: "",
    knitting_receive_id: knittingReceiveId,
    unit: "",
    color: "",
    roll: "",
    design_name: "",
});

const URL = "/create-dyeing";

// Submit handler for form
function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status === false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status === true) {
                toaster.success(page.props.flash.message);

                router.visit("/knitting-receive-list");
            }
        },
    });
}
</script>

<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <!-- Page Title -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            Add Dyeing
        </h2>

        <!-- Form Start -->
        <form @submit.prevent="submitForm" class="space-y-5">
            <!--Challan no -->
            <div>
                <label
                    for="challan_no"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Challan No
                </label>
                <input
                    v-model="form.challan_no"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>

            <!-- Dyeing Party Dropdown -->
            <div>
                <label
                    for="yarn_party"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Select Dyeing Party
                </label>
                <vSelect
                    v-model="form.dyeing_party_id"
                    :options="page.props.dyeingPartyList"
                    :reduce="(dyeing_party) => dyeing_party.id"
                    label="name"
                />
                <p
                    v-if="errors.dyeing_party_id"
                    class="text-red-500 text-md mt-1"
                >
                    {{ errors.dyeing_party_id[0] }}
                </p>
            </div>

            <!--Available Unit Field -->
            <div>
                <label
                    for="available_unit"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Available Knitting Unit
                </label>
                <input
                    :value="page.props.knittingReceive.available_unit"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                    readonly
                />
            </div>

            <!--Available Roll Field -->
            <div>
                <label
                    for="roll"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Available Roll
                </label>
                <input
                    :value="page.props.knittingReceive.roll"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                    readonly
                />
            </div>

            <!-- Unit Field -->
            <div>
                <label
                    for="unit"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Sending Unit
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

            <!-- Design Name Field -->
            <div>
                <label
                    for="design_name"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Design Name
                </label>
                <input
                    v-model="form.design_name"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
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

            <!-- Color Field -->
            <div>
                <label
                    for="color"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Color
                </label>
                <input
                    v-model="form.color"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.color" class="text-red-500 text-md mt-1">
                    {{ errors.color[0] }}
                </p>
            </div>

            <!-- Submit Button -->
            <div class="pt-3">
                <button
                    v-if="page.props.user.can['create-dyeing']"
                    type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition duration-300 cursor-pointer"
                >
                    Confirm
                </button>
            </div>
        </form>
        <!-- Form End -->
    </div>
</template>
