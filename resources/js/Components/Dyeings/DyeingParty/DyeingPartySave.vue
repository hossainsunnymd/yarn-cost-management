<script setup>

import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router } from "@inertiajs/vue3";
import { computed } from "vue";

// Setup toaster for notifications
const toaster = createToaster({});
const page = usePage();

// Extract dyeing_party_id from URL
const dyeingPartyId = new URLSearchParams(window.location.search).get("dyeing_party_id");


const dyeingParty = page.props.dyeingParty;

// Handle form validation errors
const errors = computed(() => page.props.flash.error || {});

// Initialize form fields
const form = useForm({
  dyeing_party_id: dyeingPartyId,
  name: "",
  phone: "",
  address: "",
});

// Set form values and URL conditionally for update
let URL = "/create-dyeing-party";
if (dyeingPartyId != 0 && dyeingParty != null) {
  form.name = dyeingParty.name;
  form.phone = dyeingParty.phone;
  form.address = dyeingParty.address;
  URL = "/update-dyeing-party";
}

// Submit handler
function submitForm() {
  form.post(URL, {
    preserveScroll: true,
    onSuccess: () => {
      if (page.props.flash.status === false) {
        toaster.error(page.props.flash.message);
      } else if (page.props.flash.status === true) {
        toaster.success(page.props.flash.message);
        router.visit("/dyeing-party-list");
      }
    },
  });
}
</script>


<template>
  <div class="p-6 max-w-2xl w-full mx-auto">
    <!-- Page Title -->
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
      {{ dyeingPartyId == 0 ? "Add Dyeing Party" : "Update Dyeing Party" }}
    </h2>

    <!-- Form -->
    <form @submit.prevent="submitForm" class="space-y-5">
      <!-- Name Field -->
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
        <input
          v-model="form.name"
          type="text"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <p v-if="errors.name" class="text-red-500 text-md mt-1">
          {{ errors.name[0] }}
        </p>
      </div>

      <!-- Phone Field -->
      <div>
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
        <input
          v-model="form.phone"
          type="text"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <p v-if="errors.phone" class="text-red-500 text-md mt-1">
          {{ errors.phone[0] }}
        </p>
      </div>

      <!-- Address Field -->
      <div>
        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
        <input
          v-model="form.address"
          type="text"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <p v-if="errors.address" class="text-red-500 text-md mt-1">
          {{ errors.address[0] }}
        </p>
      </div>

      <!-- Submit Button -->
      <div class="pt-3">
        <button
          type="submit"
          class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition duration-300 cursor-pointer"
        >
          {{ dyeingPartyId == 0 ? "Add Dyeing Party" : "Update Dyeing Party" }}
        </button>
      </div>
    </form>
  </div>
</template>


<style scoped></style>
