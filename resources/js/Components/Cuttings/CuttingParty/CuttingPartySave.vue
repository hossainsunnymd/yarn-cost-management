<script setup>
import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router } from "@inertiajs/vue3";
import { computed } from "vue";

// Get page props and toaster instance
const page = usePage();
const toaster = createToaster();

// Handle validation errors
const errors = computed(() => page.props.flash.error || {});

// Get sewing party ID from query params
const cuttingPartyId = new URLSearchParams(window.location.search).get(
    "cutting_party_id"
);
const cuttingParty = page.props.cuttingParty;

// Form setup
const form = useForm({
    cutting_party_id: cuttingPartyId,
    name: "",
    phone: "",
    address: "",
});

// Set form values and URL conditionally for update
let URL = "/create-cutting-party";
if (cuttingPartyId != 0 && cuttingParty != null) {
    form.name = cuttingParty.name;
    form.phone = cuttingParty.phone;
    form.address = cuttingParty.address;
    URL = "/update-cutting-party";
}

// Form submission handler
function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status===false) {
                toaster.error(page.props.flash.message);
                
            } else if (page.props.flash.status===true) {
                toaster.success(page.props.flash.message);
                router.visit("/cutting-party-list");
            }
        },
    });
}
</script>

<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <!-- Title -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            {{
                cuttingPartyId == 0 ? "Add Cutting Party" : "Update Cutting Party"
            }}
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
                    id="name"
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
                    id="phone"
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
                    id="address"
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
                        cuttingPartyId == 0
                            ? "Add Cutting Party"
                            : "Update Cutting Party"
                    }}
                </button>
            </div>
        </form>
    </div>
</template>
