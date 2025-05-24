<script setup>
import { computed } from "vue";
import { usePage, useForm, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster({});

const page = usePage();

const yarnPurchaseId = new URLSearchParams(window.location.search).get("id");
const yarnPurchase = page.props.yarnPurchase;
const errors = computed(() => page.props.flash.errors || {});


let URL = "/create-yarn-purchase";

const form = useForm({
  yarn_party_id: "",
  lot_no: "",
  unit: 0,
  name: "",
  description: "",
  weight: 0,
  bags: 0,
  unit: 0,
  yarn_rate: 0,
  bill_amount: 0,
  labour_cost: 0,
  total_amount: 0,
  id : yarnPurchaseId


});



if (yarnPurchaseId != 0 && yarnPurchase != null) {
  form.yarn_party_id = yarnPurchase.yarn_party_id;
  form.lot_no = yarnPurchase.lot_no;
  form.unit = yarnPurchase.unit;
  form.name = yarnPurchase.name;
  form.description = yarnPurchase.description;
  form.weight = yarnPurchase.weight;
  form.bags = yarnPurchase.bags;
  form.yarn_rate = yarnPurchase.yarn_rate;
  form.bill_amount = yarnPurchase.bill_amount;
  form.labour_cost = yarnPurchase.labour_cost;
  form.total_amount = yarnPurchase.total_amount;

  URL = "/update-yarn-purchase";
}

function submitForm() {

  form.post(URL, {
    preserveScroll: true,
    onSuccess: () => {
      if (page.props.flash.status == false) {
        toaster.error(page.props.flash.message);
      } else if (page.props.flash.status == true) {
        toaster.success(page.props.flash.message);
        router.get("/yarn-purchase-list");
      }
    },
  });
}
</script>

<template>
  <div class="p-6 max-w-2xl w-full mx-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Add Stock Entry</h2>

    <form  class="space-y-5">

        <div>
        <label for="yarn_party" class="block text-sm font-medium text-gray-700 mb-1"> Select Yarn Party</label>
        <select
          v-model="form.yarn_party_id"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
          <option value="" disabled>Select Yarn Party</option>
          <option
            v-for="party in page.props.yarnParty"
            :key="party.id"
            :value="party.id"
          >
            {{ party.name }}
          </option>
        </select>
      </div>


      <div>
        <label for="lot_no" class="block text-sm font-medium text-gray-700 mb-1">Lot No</label>
        <input
          v-model="form.lot_no"
          type="text"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
      </div>


      <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
       <input
          v-model="form.name"
          type="text"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
      </div>

      <div>
        <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
       <input
          v-model="form.unit"
          type="number"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
      </div>

       <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
       <input
          v-model="form.description"
          type="description"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
      </div>

      <div>
        <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Weight</label>
        <input
          v-model="form.weight"
          type="text"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
      </div>

       <div>
        <label for="bags" class="block text-sm font-medium text-gray-700 mb-1">Bags</label>
        <input
          v-model="form.bags"
          type="number"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
      </div>

       <div>
        <label for="yarn_rate" class="block text-sm font-medium text-gray-700 mb-1">Yarn Rate</label>
        <input
          v-model="form.yarn_rate"
          type="text"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
      </div>

       <div>
        <label for="bill_amount" class="block text-sm font-medium text-gray-700 mb-1">Bill Amount</label>
        <input
          v-model="form.bill_amount"
          type="text"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
      </div>

       <div>
        <label for="labour_cost" class="block text-sm font-medium text-gray-700 mb-1"> Labour Cost</label>
        <input
          v-model="form.labour_cost"
          type="text"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
      </div>

       <div>
        <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
        <input
          v-model="form.total_amount"
          type="text"
          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
      </div>

      <!-- Submit Button -->
      <div class="pt-3">
        <button @click="submitForm"
          type="button"
          class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition duration-300"
        >
          {{ yarnPurchaseId == 0 ? 'Add Purchase' : 'Update Purchase' }}
        </button>
      </div>

    </form>
  </div>
</template>

<style scoped></style>
