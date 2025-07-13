<script setup>
import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router } from "@inertiajs/vue3";
import { computed } from "vue";

// Access flash messages and page data
const page = usePage();
const toaster = createToaster();

// Collect validation errors from flash
const errors = computed(() => page.props.flash.error || {});

// Get party ID from query parameter
const knittingPartyId = new URLSearchParams(window.location.search).get("knitting_party_id");

// Get existing party data (if any)
const knittingParty = page.props.knittingParty;

// Create form with default values
const form = useForm({
    knitting_party_id: knittingPartyId,
    name: "",
    phone: "",
    address: "",
});

// If editing, populate form and change API endpoint
let URL = "/create-knitting-party";
if (knittingPartyId != 0 && knittingParty != null) {
    form.name = knittingParty.name;
    form.phone = knittingParty.phone;
    form.address = knittingParty.address;
    URL = "/update-knitting-party";
}

// Handle form submission
function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            const flash = page.props.flash;
            if (flash.status === false) {
                toaster.error(flash.message);
            } else if (flash.status === true) {
                toaster.success(flash.message);
                router.visit("/knitting-party-list");
            }
        },
    });
}
</script>


<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <!-- Dynamic heading based on create or update -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            {{ knittingPartyId == 0 ? "Add Knitting Party" : "Update Knitting Party" }}
        </h2>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-5">
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.name" class="text-red-500 text-md mt-1">{{ errors.name[0] }}</p>
            </div>

            <!-- Phone Field -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input
                    v-model="form.phone"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.phone" class="text-red-500 text-md mt-1">{{ errors.phone[0] }}</p>
            </div>

            <!-- Address Field -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <input
                    v-model="form.address"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.address" class="text-red-500 text-md mt-1">{{ errors.address[0] }}</p>
            </div>

            <!-- Submit Button -->
            <div class="pt-3">
                <button
                    type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition duration-300"
                >
                    {{ knittingPartyId == 0 ? "Add Knitting Party" : "Update Knitting Party" }}
                </button>
            </div>
        </form>
    </div>
</template>


<style scoped></style>
