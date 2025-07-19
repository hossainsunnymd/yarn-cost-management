<script setup>
import { useForm, usePage, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

// Setup toaster for notifications
const toaster = createToaster({});
const page = usePage();

// Props received from parent
const props = defineProps({
  paymentModal: Boolean,
  paymentId: String,
});


const emit = defineEmits(["update:paymentModal"]);

// Form data for payment
const form = useForm({
  amount: "",
});

// Handle confirm button click
function confirmPayment() {
  if (form.amount === "") {
    toaster.error("Please enter amount");
    return;
  }

  // Post the form to the backend
  form.post(`/save-customer-payment?customer_id=${props.paymentId}`, {
    preserveScroll: true,
    onSuccess: () => {
      const flash = page.props.flash;
      if (flash.status) {
        toaster.success(flash.message);
        router.visit(`/customer-payment-list?customer_id=${props.paymentId}`);
      } else {
        toaster.error(flash.message);

      }
    },
  });

  // Close the modal after submission
  emit("update:paymentModal", false);
}
</script>


<template>
  <!-- Payment Modal -->
  <div
    v-if="paymentModal"
    class="fixed inset-0 bg-black/15 bg-opacity-50 flex items-center justify-center z-50"
  >
    <!-- Modal Box -->
    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">

      <!-- Amount Input -->
      <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
        Amount
      </label>
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


<style scoped></style>
