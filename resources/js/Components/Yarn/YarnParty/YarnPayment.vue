<script setup>
import { useForm, usePage, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

// Toast notification instance
const toaster = createToaster({});
const page = usePage();

// Props received from parent component
const props = defineProps({
    paymentModal: Boolean,
    paymentId: Number,
});

// Form state
const form = useForm({
    amount: "",
});

// Emit event to update modal visibility
const emit = defineEmits(["update:paymentModal"]);

// Handle payment confirmation and form submission
function confirmPayment() {
    if (!form.amount) {
        toaster.error("Please enter amount");
        return;
    }

    form.post(`/save-yarn-payment?yarn_party_id=${props.paymentId}`, {
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
            <label for="amount" class="block text-sm font-medium mb-1"
                >Amount</label
            >
            <input
                v-model="form.amount"
                type="text"
                class="border border-gray-300 rounded-md px-4 py-2 w-full"
                placeholder="Enter amount"
            />

            <!-- Action Buttons -->
            <div class="flex justify-end mt-6 space-x-2">
                <!-- Cancel Button -->
                <button
                    @click="$emit('update:paymentModal', false)"
                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400"
                >
                    Cancel
                </button>
                <!-- Confirm Button -->
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
