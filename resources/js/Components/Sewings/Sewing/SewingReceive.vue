<script setup>
import { computed } from "vue";
import { usePage, useForm, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import ImageUpload from "../../ImageUpload.vue";

// Access page props and toaster
const page = usePage();
const toaster = createToaster({});

//  sewing_id from URL query
const sewingId = new URLSearchParams(window.location.search).get("sewing_id");

// Handle backend validation errors
const errors = computed(() => page.props.flash.error || {});

// Form setup using Inertia's useForm
const form = useForm({
    sewing_id: sewingId,
    unit: "",
    wastage: "",
    sewing_cost: "",
    extra_cost: "",
    image: "",
});

//calculate total sewing cost
const totalSewingCost = computed(() => {
    return form.sewing_cost * form.unit;
});

// Form submission URL
const URL = "/create-sewing-receive";

// Submit form to backend
function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            const flash=page.props.flash;
            if (flash.status===true) {
                toaster.success(flash.message);
                router.visit("/sewing-list");
            } else if (flash.status===false) {
                toaster.error(flash.message);
            }
        },
    });
}
</script>

<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <!-- Page Title -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            Receive Product
        </h2>

        <!-- Product Receive Form -->
        <form @submit.prevent="submitForm" class="space-y-5">
            <!--Available Unit Input -->
            <div>
                <label
                    for="available_unit"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Available Pcs
                </label>
                <input
                    :value="page.props.sewing.available_unit"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>

            <!-- Unit Input -->
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

            <!-- Sewing Cost Input -->
            <div>
                <label
                    for="sewing_cost"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Sewing Cost
                </label>
                <input
                    v-model="form.sewing_cost"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.sewing_cost" class="text-red-500 text-md mt-1">
                    {{ errors.sewing_cost[0] }}
                </p>
            </div>

            <!--Tota Sewing Cost Input -->
            <div>
                <label
                    for="total_sewing_cost"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Total Sewing Cost
                </label>
                <input
                    :value="totalSewingCost"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>

            <!--Extra Input -->
            <div>
                <label
                    for="extra_cost"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Extra Cost
                </label>
                <input
                    v-model="form.extra_cost"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>

            <!-- Wastage Input -->
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

            <!-- Image Upload Section -->
            <div>
                <label
                    for="image"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Product Image
                </label>
                <ImageUpload
                    :productImage="form.image"
                    @image="(e) => (form.image = e)"
                />
                <p v-if="errors.image" class="text-red-500 mt-1">
                    {{ errors.image[0] }}
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
