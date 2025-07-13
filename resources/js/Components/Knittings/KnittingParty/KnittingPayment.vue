<script setup>
import { useForm, usePage, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

// Toast notification setup
const toaster = createToaster();
const page = usePage();

// Props received from parent
const props = defineProps({
    paymentModal: Boolean,
    paymentId: Number,
});

// Emit to close modal
const emit = defineEmits(["update:paymentModal"]);

// Payment form
const form = useForm({
    amount: "",
});

// Submit payment function
function confirmPayment() {
    if (form.amount === "") {
        toaster.error("Please enter amount");
        return;
    }

    form.post(`/save-knitting-payment?knitting_party_id=${props.paymentId}`, {
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

    emit("update:paymentModal", false);
}
</script>


<template>
    <!-- Modal Overlay -->
    <div
        v-if="paymentModal"
        class="fixed inset-0 bg-black/15 bg-opacity-50 flex items-center justify-center z-50"
    >
        <!-- Modal Box -->
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
            <!-- Amount Input -->
            <div class="mb-4">
                <label for="amount" class="block mb-1 font-medium">Amount</label>
                <input
                    v-model="form.amount"
                    type="text"
                    class="border border-gray-300 rounded-md px-4 py-2 w-full"
                    placeholder="Enter payment amount"
                />
            </div>

            <!-- Action Buttons -->
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
