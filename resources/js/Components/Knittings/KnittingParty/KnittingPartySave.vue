<script setup>
import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router } from "@inertiajs/vue3";
import { computed } from "vue";

const errors = computed(() => page.props.flash.error || {});
const toaster = createToaster({});
const page = usePage();
const knittingPartyId = new URLSearchParams(window.location.search).get(
    "knitting_party_id"
);
const knittingParty = page.props.knittingParty;

const form = useForm({
    knitting_party_id: knittingPartyId,
    name: "",
    phone: "",
    address: "",
});
let URL = "/create-knitting-party";
if (knittingPartyId != 0 && knittingParty != null) {
    form.name = knittingParty.name;
    form.phone = knittingParty.phone;
    form.address = knittingParty.address;
    URL = "/update-knitting-party";
}

function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status == false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status == true) {
                toaster.success(page.props.flash.message);
                router.visit("/knitting-party-list");
            }
        },
    });
}
</script>

<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            {{ knittingPartyId == 0 ? "Add knitting Party" : "Update knitting Party" }}
        </h2>

        <form @submit.prevent="submitForm" class="space-y-5">
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
                <p v-if="errors.phone" class="text-red-500 text-md mt-1">
                    {{ errors.phone[0] }}
                </p>
            </div>

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
                <p v-if="errors.address" class="text-red-500 text-md mt-1">
                    {{ errors.address[0] }}
                </p>
            </div>

            <div class="pt-3">
                <button
                    type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition duration-300"
                >
                    {{ knittingPartyId == 0 ? "Add Yarn Party" : "Update Yarn Party" }}
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped></style>
