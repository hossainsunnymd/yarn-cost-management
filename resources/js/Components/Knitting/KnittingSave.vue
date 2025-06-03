<script setup>
import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router } from "@inertiajs/vue3";
import { computed } from "vue";

const errors = computed(() => page.props.flash.errors || {});
const toaster = createToaster({});
const page = usePage();
const yarnPurchaseId = new URLSearchParams(window.location.search).get(
    "yarn_purchase_id"
);

const form = useForm({
    knitting_party_id: "",
    yarn_purchase_id: yarnPurchaseId,
    unit: "",
});
let URL = "/create-knitting";

function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status == false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status == true) {
                toaster.success(page.props.flash.message);
                router.visit("/yarn-purchase-list");
            }
        },
    });
}
</script>

<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            Add Knitting
        </h2>

        <form @submit.prevent="submitForm" class="space-y-5">
            <div>
                <label
                    for="yarn_party"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Select Knitting Party</label
                >
                <select
                    v-model="form.knitting_party_id"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                    <option value="" disabled>Select Knitting Party</option>
                    <option
                        v-for="party in page.props.knittingPartyList"
                        :key="party.id"
                        :value="party.id"
                    >
                        {{ party.name }}
                    </option>
                </select>
                <p v-if="errors.knitting_party_id" class="text-red-500 text-md mt-1">
                    {{ errors.knitting_party_id[0] }}
                </p>
            </div>

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

<style scoped></style>
