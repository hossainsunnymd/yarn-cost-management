<script setup>
import { usePage, useForm, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { computed } from "vue";

const page = usePage();
const toaster = createToaster({});

// Compute errors from flash message errors (if any)
const errors = computed(() => page.props.flash.error || {});

// Get cutting_id from URL query parameters
const cuttingId = new URLSearchParams(window.location.search).get("cutting_id");

// Initialize the form with default values and the cutting_id
const form = useForm({
    cutting_id: cuttingId,
    unit: "",
    per_unit_cutting_cost: "",
});

//calculate total cutting cost
const totalCuttingCost = computed(() => {
    return form.per_unit_cutting_cost * form.unit;
});



// API endpoint for submitting the form
const URL = "/create-cutting-receive";

// Submit form handler
function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status === false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status === true) {
                toaster.success(page.props.flash.message);
                router.visit("/cutting-list");
            }
        },
    });
}
</script>

<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            Cutting Receive
        </h2>

        <form @submit.prevent="submitForm" class="space-y-5">
            <!--Available Unit input -->
            <div>
                <label
                    for="available_unit"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Unit
                </label>
                <input
                    :value="page.props.cutting.available_unit"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                    readonly
                />
                <p v-if="errors.unit" class="text-red-500 text-md mt-1">
                    {{ errors.unit[0] }}
                </p>
            </div>
            <!-- Unit input -->
            <div>
                <label
                    for="unit"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Pcs
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

            <!--Per Cutting Cost input -->
            <div>
                <label
                    for="per_unit_cutting_cost"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Per Unit Cutting Cost
                </label>
                <input
                    v-model="form.per_unit_cutting_cost"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p
                    v-if="errors.per_unit_cutting_cost"
                    class="text-red-500 text-md mt-1"
                >
                    {{ errors.per_unit_cutting_cost[0] }}
                </p>
            </div>

            <!--Total Cutting Cost input -->
            <div>
                <label
                    for="total_cutting_cost"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Total Cutting Cost
                </label>
                <input
                    :value="totalCuttingCost"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                    readonly
                />
            </div>

              <!--Wastage -->


            <!-- Submit button -->
            <div class="pt-3">
                <button v-if="page.props.user.can['create-cutting-receive']"
                    type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition duration-300"
                >
                    Confirm
                </button>
            </div>
        </form>
    </div>
</template>
