<script setup>
import { computed } from "vue";
import { usePage, useForm, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

import vSelect from "vue3-select";
import "vue3-select/dist/vue3-select.css";

const toaster = createToaster({});
const page = usePage();

// Get yarn purchase ID from URL query parameter
const yarnPurchaseId = new URLSearchParams(window.location.search).get("id");

// Get yarn purchase data passed from backend
const yarnPurchase = page.props.yarnPurchase;

// Computed errors object from flash messages
const errors = computed(() => page.props.flash.error || {});

// Default POST URL for creating yarn purchase
let URL = "/create-yarn-purchase";

// Initialize form with default or existing data
const form = useForm({
    challan_no: "",
    yarn_party_id: "",
    unit: 0,
    name: "",
    description: "",
    bags: 0,
    yarn_rate: 0,
    labour_cost: 0,
    id: yarnPurchaseId,
});

// If editing existing purchase, populate form and change URL to update endpoint
if (yarnPurchaseId != 0 && yarnPurchase != null) {
    form.challan_no = yarnPurchase.challan_no;
    form.yarn_party_id = yarnPurchase.yarn_party_id;
    form.unit = yarnPurchase.unit;
    form.name = yarnPurchase.name;
    form.description = yarnPurchase.description;
    form.bags = yarnPurchase.bags;
    form.yarn_rate = yarnPurchase.yarn_rate;
    form.labour_cost = yarnPurchase.labour_cost;
    URL = "/update-yarn-purchase";
}

// Submit form via POST request (create or update)
function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status == false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status == true) {
                toaster.success(page.props.flash.message);
                router.get("/yarn-purchase-list");
            }
        },
    });
}
</script>

<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <!-- Title: dynamically changes based on create or update -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            {{ yarnPurchaseId == 0 ? "Add Stock Entry" : "Update Stock Entry" }}
        </h2>

        <form class="space-y-5" @submit.prevent="submitForm">
            <!-- Yarn Party Select -->
            <div>
                <label
                    for="yarn_party"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Select Yarn Party
                </label>
                <vSelect
                    v-model="form.yarn_party_id"
                    :options="page.props.yarnParties"
                    label="name"
                    :reduce="(yarn_party) => yarn_party.id"
                    placeholder="Select or type Yarn Party"
                    class="custom-input"
                />
                <p
                    v-if="errors.yarn_party_id"
                    class="text-red-500 text-md mt-1"
                >
                    {{ errors.yarn_party_id[0] }}
                </p>
            </div>

            <!-- Chalan no -->
            <div>
                <label
                    for="challan_no"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Challan No</label
                >
                <input
                    v-model="form.challan_no"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.challan_no" class="text-red-500 text-md mt-1">
                    {{ errors.challan_no[0] }}
                </p>
            </div>

            <!-- Name -->
            <div>
                <label
                    for="name"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Name</label
                >
                <input
                    v-model="form.name"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.name" class="text-red-500 text-md mt-1">
                    {{ errors.name[0] }}
                </p>
            </div>

            <!-- Description -->
            <div>
                <label
                    for="description"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Description</label
                >
                <input
                    v-model="form.description"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.description" class="text-red-500 text-md mt-1">
                    {{ errors.description[0] }}
                </p>
            </div>

            <!-- Weight (unit) -->
            <div>
                <label
                    for="weight"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Weight</label
                >
                <input
                    v-model="form.unit"
                    type="number"
                    min="0"
                    step="any"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.unit" class="text-red-500 text-md mt-1">
                    {{ errors.unit[0] }}
                </p>
            </div>

            <!-- Bags -->
            <div>
                <label
                    for="bags"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Bags</label
                >
                <input
                    v-model="form.bags"
                    type="number"
                    min="0"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.bags" class="text-red-500 text-md mt-1">
                    {{ errors.bags[0] }}
                </p>
            </div>

            <!-- Yarn Rate -->
            <div>
                <label
                    for="yarn_rate"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Yarn Rate</label
                >
                <input
                    v-model="form.yarn_rate"
                    type="number"
                    min="0"
                    step="any"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.yarn_rate" class="text-red-500 text-md mt-1">
                    {{ errors.yarn_rate[0] }}
                </p>
            </div>

            <!-- Labour Cost -->
            <div>
                <label
                    for="labour_cost"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Labour Cost</label
                >
                <input
                    v-model="form.labour_cost"
                    type="number"
                    min="0"
                    step="any"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.labour_cost" class="text-red-500 text-md mt-1">
                    {{ errors.labour_cost[0] }}
                </p>
            </div>

            <!-- Submit Button -->
            <div class="pt-3">
                <button
                    type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition duration-300 cursor-pointer"
                >
                    {{
                        yarnPurchaseId == 0 ? "Add Purchase" : "Update Purchase"
                    }}
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped>




</style>
