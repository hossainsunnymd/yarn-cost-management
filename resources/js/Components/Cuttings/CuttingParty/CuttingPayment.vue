<script setup>
import { useForm, usePage, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

const props = defineProps({
    paymentModal: Boolean,
    paymentId: Number,
});

// Emits Allows parent to control modal visibility
const emit = defineEmits(["update:paymentModal"]);

const page = usePage();
const toaster = createToaster();

// Payment form
const form = useForm({
    amount: "",
});

// Submit payment handler
function confirmPayment() {
    if (!form.amount) {
        return toaster.error("Please enter amount");
    }

    form.post(`/save-cutting-payment?cutting_party_id=${props.paymentId}`, {
        preserveScroll: true,
        onSuccess: () => {
            const flash = page.props.flash;
            if (flash.status) {
                toaster.success(flash.message);
                router.visit("/cutting-party-list");
            } else {
                toaster.error(flash.message);
            }
        },
    });

    emit("update:paymentModal", false); // Close modal
}
</script>

<template>
    <!-- Modal Overlay -->
    <div
        v-if="paymentModal"
        class="fixed inset-0 bg-black/15 bg-opacity-50 flex items-center justify-center z-50"
    >
        <!-- Modal Container -->
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
            <!-- Amount Field -->
            <label for="amount" class="block mb-1 font-medium text-sm"
                >Amount</label
            >
            <input
                v-model="form.amount"
                id="amount"
                type="text"
                class="border border-gray-300 rounded-md px-4 py-2 w-full"
            />

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
