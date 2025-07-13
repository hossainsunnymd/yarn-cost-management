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
    image: "",
});

// Form submission URL
const URL = "/create-sewing-receive";

// Submit form to backend
function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status === false) {
                toaster.error(page.props.flash.message);
            } else {
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
            Receive Product
        </h2>

        <!-- Product Receive Form -->
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
