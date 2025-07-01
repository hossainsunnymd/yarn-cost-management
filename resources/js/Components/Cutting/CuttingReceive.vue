<script setup>
import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router } from "@inertiajs/vue3";

const toaster = createToaster({});
const page = usePage();
const cuttingId = new URLSearchParams(window.location.search).get(
    "cutting_id"
);

const form = useForm({
    cutting_id: cuttingId,
    unit: "",
    wastage: "",
    cutting_cost: "",

});
let URL = "/create-cutting-receive";

function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status == false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status == true) {
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
            </div>

              <div>
                <label
                    for="t"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Cutting Cost</label
                >
                <input
                    v-model="form.cutting_cost"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>
              <div>
                <label
                    for="t"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Wastage</label
                >
                <input
                    v-model="form.wastage"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
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
