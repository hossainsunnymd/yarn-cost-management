<script setup>
import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router } from "@inertiajs/vue3";

const toaster = createToaster({});
const page = usePage();
const dyeingReceiveId = new URLSearchParams(window.location.search).get(
    "dyeing_receive_id"
);
const form = useForm({
    dyeing_receive_id: dyeingReceiveId,
    unit: "",
    total_amount: "",
});


function submitForm() {
    form.post('/create-fabric-sale', {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status == false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status == true) {
                toaster.success(page.props.flash.message);
                router.visit("/fabric-list");
            }
        },
    });
}
</script>

<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            Add Fabric Sale
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
                    type="number"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>

               <div>
                <label
                    for="amount"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Amount</label
                >
                <input
                    v-model="form.total_amount"
                    type="number"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>

            <div class="pt-3">
                <button
                    type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition duration-300"
                >
                 Add Fabric Sale
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped></style>
