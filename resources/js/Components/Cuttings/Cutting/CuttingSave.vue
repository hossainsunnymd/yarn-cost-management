<script setup>
// Import necessary Vue and Inertia helpers
import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router } from "@inertiajs/vue3";
import { computed } from "vue";

// Access global page props
const page = usePage();

// Handle validation errors from flash message
const errors = computed(() => page.props.flash.error || {});

// Setup toaster for notifications
const toaster = createToaster({});

// Get dyeing_receive_id from query parameters
const dyeingReceiveId = new URLSearchParams(window.location.search).get("dyeing_receive_id");

// Form data definition
const form = useForm({
  category_id: "",
  dyeing_receive_id: dyeingReceiveId,
  cutting_party_id: "",
  unit: "",
  roll: "",
});



// API route for submission
const URL = "/create-cutting";

// Handle form submission
function submitForm() {
  form.post(URL, {
    preserveScroll: true,
    onSuccess: () => {
      if (page.props.flash.status === false) {
        toaster.error(page.props.flash.message);
      } else if (page.props.flash.status === true) {
        toaster.success(page.props.flash.message);
        router.visit("/cutting-list");
      }
    },
  });
}
</script>

<template>
  <div class="p-6 max-w-2xl w-full mx-auto">
    <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">
      Add Cutting
    </h2>

    <form @submit.prevent="submitForm" class="space-y-5">


           <!-- Cutting party Dropdown -->
      <div>
        <label for="cutting_party_id" class="block text-sm font-medium text-gray-700 mb-1">
          Select Cutting Party
        </label>
        <select
          v-model="form.cutting_party_id"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
          <option value="" disabled>Select Cutting Party</option>
          <option
            v-for="cutting in page.props.cuttingParty"
            :key="cutting.id"
            :value="cutting.id"
          >
            {{ cutting.name }}
          </option>
        </select>
        <p v-if="errors.category_id" class="text-red-500 text-md mt-1">
          {{ errors.category_id[0] }}
        </p>
      </div>

      <!-- Category Dropdown -->
      <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
          Select Category
        </label>
        <select
          v-model="form.category_id"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
          <option value="" disabled>Select Category</option>
          <option
            v-for="category in page.props.categories"
            :key="category.id"
            :value="category.id"
          >
            {{ category.name }}
          </option>
        </select>
        <p v-if="errors.category_id" class="text-red-500 text-md mt-1">
          {{ errors.category_id[0] }}
        </p>
      </div>

      <!-- Available Unit Input -->
      <div>
        <label for="available_unit" class="block text-sm font-medium text-gray-700 mb-1">
          Available Unit
        </label>
        <input
          :value = "page.props.dyeingReceive.available_unit"
          type="text"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
          readonly
        />
      </div>

        <!-- Available Roll Input -->
      <div>
        <label for="available_roll" class="block text-sm font-medium text-gray-700 mb-1">
          Available Roll
        </label>
        <input
          :value = "page.props.dyeingReceive.roll"
          type="text"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
          readonly
        />
      </div>

      <!-- Unit Input -->
      <div>
        <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">
          Unit
        </label>
        <input
          v-model="form.unit"
          type="text"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <p v-if="errors.unit" class="text-red-500 text-md mt-1">{{ errors.unit[0] }}</p>
      </div>

      <!-- Roll Input -->
      <div>
        <label for="roll" class="block text-sm font-medium text-gray-700 mb-1">
          Roll
        </label>
        <input
          v-model="form.roll"
          type="text"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <p v-if="errors.roll" class="text-red-500 text-md mt-1">{{ errors.roll[0] }}</p>
      </div>

      <!-- Submit Button -->
      <div class="pt-3">
        <button v-if="page.props.user.can['create-cutting']"
          type="submit"
          class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition duration-300 cursor-pointer"
        >
          Confirm
        </button>
      </div>
    </form>
  </div>
</template>

