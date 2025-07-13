<script setup>
import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router, Link } from "@inertiajs/vue3";
import { computed } from "vue";

// Access the current page props
const page = usePage();

// Reactive computed property to get validation errors from flash messages
const errors = computed(() => page.props.flash.error || {});

// Initialize toaster for notifications
const toaster = createToaster({});

// Get customer ID from URL query parameters
const customerId = new URLSearchParams(window.location.search).get("customer_id");

// Get customer data from backend props (if editing existing customer)
const customer = page.props.customer;

// Define form state with Inertia's useForm, initialized empty or with existing data
const form = useForm({
  customer_id: customerId,
  name: "",
  phone: "",
  address: "",
});

// Determine URL for create or update API endpoint
let URL = "/create-customer";
if (customerId != 0 && customer != null) {
  form.name = customer.name;
  form.phone = customer.phone;
  form.address = customer.address;
  URL = "/update-customer";
}

// Submit form handler
function submitForm() {
  form.post(URL, {
    preserveScroll: true,
    onSuccess: () => {
      // Show error or success toaster messages based on flash status
      if (page.props.flash.status == false) {
        toaster.error(page.props.flash.message);
      } else if (page.props.flash.status == true) {
        toaster.success(page.props.flash.message);
        // Redirect to customer list page on success
        router.visit("/customer-list");
      }
    },
  });
}
</script>

<template>
  <div class="container mx-auto py-8 px-4">
    <form
      @submit.prevent="submitForm"
      class="max-w-lg mx-auto bg-white p-8 rounded-md shadow-md"
    >
      <!-- Back button to customer list -->
      <div class="flex justify-end mb-4">
        <Link
          href="/customer-list"
          class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md transition duration-300"
        >
          Back
        </Link>
      </div>

      <!-- Form title changes based on add or update -->
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        {{ customerId == 0 ? "Add Customer" : "Update Customer" }}
      </h2>

      <!-- Name input field with validation error message -->
      <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
          Name
        </label>
        <input
          v-model="form.name"
          type="text"
          id="name"
          class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <p v-if="errors.name" class="text-red-500 mt-1">{{ errors.name[0] }}</p>
      </div>

      <!-- Phone input field with validation error message -->
      <div class="mb-4">
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
          Phone
        </label>
        <input
          v-model="form.phone"
          type="text"
          id="phone"
          class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <p v-if="errors.phone" class="text-red-500 mt-1">{{ errors.phone[0] }}</p>
      </div>

      <!-- Address input field with validation error message -->
      <div class="mb-6">
        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
          Address
        </label>
        <input
          v-model="form.address"
          type="text"
          id="address"
          class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <p v-if="errors.address" class="text-red-500 mt-1">{{ errors.address[0] }}</p>
      </div>

      <!-- Submit button -->
      <button
        type="submit"
        class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-xl transition duration-300"
      >
        {{ customerId == 0 ? "Add Customer" : "Update Customer" }}
      </button>
    </form>
  </div>
</template>

<style scoped></style>
