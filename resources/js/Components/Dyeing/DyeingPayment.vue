<script setup>
import { useForm, usePage, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({});

const page = usePage();
const props = defineProps({
    paymentModal: Boolean,
    paymentId: Number,
});

const form = useForm({
    amount: "",
});

const emit = defineEmits(["update:paymentModal"]);

function confirmPayment() {
    if (form.amount == "") {
        toaster.error("Please enter amount");
        return;
    } else if (form.payment_date == "") {
        toaster.error("Please enter payment date");
        return;
    } else {
        form.post(`/save-dyeing-payment?dyeing_party_id=${props.paymentId}`, {
            preserveScroll: true,
            onSuccess: () => {
                if (page.props.flash.status == false) {
                    toaster.error(page.props.flash.message);
                } else if (page.props.flash.status == true) {
                    toaster.success(page.props.flash.message);
                    router.visit("/dyeing-party-list");
                }
            },
        });
        emit("update:paymentModal", false);
    }
}
</script>

<template>
    <div
        v-if="paymentModal"
        class="fixed inset-0 bg-black/15 bg-opacity-50 flex items-center justify-center z-50"
    >
        <!-- Modal Box -->
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
            <label for="amount">Amount</label>
            <input
                v-model="form.amount"
                class="border border-gray-300 rounded-md px-4 py-2 w-full"
                type="text"
            />

            <!-- Action buttons -->
            <div class="flex justify-end mt-6 space-x-2">
                <button
                    @click="$emit('update:paymentModal', false)"
                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400"
                >
                    Cancel
                </button>
                <button
                    @click="confirmPayment"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                >
                    Confirm
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
