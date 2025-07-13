<script setup>
// Imports
import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router } from "@inertiajs/vue3";
import { computed } from "vue";

// Initialize toaster and page reference
const toaster = createToaster({});
const page = usePage();

// Access validation errors from flash
const errors = computed(() => page.props.flash.error || {});

// Get yarn party ID from query parameter
const yarnPartyId = new URLSearchParams(window.location.search).get(
    "yarn_party_id"
);

// Get yarn party data from page props
const yarnParty = page.props.yarnParty;

// Form state
const form = useForm({
    yarn_party_id: yarnPartyId,
    name: "",
    phone: "",
    address: "",
});

// Determine URL and fill form if editing
let URL = "/create-yarn-party";
if (yarnPartyId != 0 && yarnParty != null) {
    form.name = yarnParty.name;
    form.phone = yarnParty.phone;
    form.address = yarnParty.address;
    URL = "/update-yarn-party";
}

// Submit form handler
function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            const flash = page.props.flash;
            if (flash.status === false) {
                toaster.error(flash.message);
            } else if (flash.status === true) {
                toaster.success(flash.message);
                router.visit("/yarn-party-list");
            }
        },
    });
}
</script>

<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <!-- Title -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            {{ yarnPartyId == 0 ? "Add Yarn Party" : "Update Yarn Party" }}
        </h2>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-5">
            <!-- Name Field -->
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
                <p v-if="errors.name" class="text-red-500 text-sm mt-1">
                    {{ errors.name[0] }}
                </p>
            </div>

            <!-- Phone Field -->
            <div>
                <label
                    for="phone"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Phone</label
                >
                <input
                    v-model="form.phone"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.phone" class="text-red-500 text-sm mt-1">
                    {{ errors.phone[0] }}
                </p>
            </div>

            <!-- Address Field -->
            <div>
                <label
                    for="address"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Address</label
                >
                <input
                    v-model="form.address"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.address" class="text-red-500 text-sm mt-1">
                    {{ errors.address[0] }}
                </p>
            </div>

            <!-- Submit Button -->
            <div class="pt-3">
                <button
                    type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition duration-300"
                >
                    {{
                        yarnPartyId == 0
                            ? "Add Yarn Party"
                            : "Update Yarn Party"
                    }}
                </button>
            </div>
        </form>
    </div>
</template>
