<template>
  <!-- Modal -->
  <div
    v-if="showModal"
    class="fixed inset-0 z-50 flex items-start justify-center overflow-auto bg-black/15 bg-opacity-40 pt-20"
  >
    <div
      class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4"
      role="dialog"
      aria-modal="true"
      aria-labelledby="modal-title"
    >
      <div class="flex justify-between items-center px-4 py-2 rounded-t-lg">
        <h1 id="modal-title" class="text-xl font-bold text-black">Add Quantity</h1>
        <button
          type="button"
          class="text-white text-2xl font-bold bg-red-500 hover:bg-red-600 rounded-sm w-8 h-8 flex items-center justify-center"
          @click="closeModal"
          aria-label="Close"
        >
          &times;
        </button>
      </div>
      <div class="px-6 py-4 space-y-4">
        <div>
          <label for="weight" class="block font-semibold mb-1">Weight</label>
          <input v-model="weight" type="number" id="weight" class="w-full border px-3 py-2 rounded" />
        </div>
      </div>
      <div class="flex justify-end px-6 py-3 rounded-b-lg">
        <button
          @click="addProduct"
          type="button"
          class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded"
        >
          Add
        </button>
      </div>
    </div>
  </div>

  <!-- Main Container -->
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Create Sale</h1>

    <div class="flex flex-col md:flex-row md:space-x-6">
      <!-- Customer Column -->
      <div class="md:w-1/2 mb-6 md:mb-0">
        <div class="border rounded p-4 shadow-sm">
          <p class="font-semibold mb-3">Select Customer</p>
          <input
            v-model="searchCustomer"
            type="text"
            placeholder="Search Party..."
            class="w-full mb-4 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <EasyDataTable
            buttons-paginations
            alternating
            :items="customerItem"
            :headers="customerHeaders"
            :rows-per-page="10"
          >
            <template #item-action="{ name, phone, id }">
              <button
                @click="addCustomer(name, phone, id)"
                type="button"
                class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded"
              >
                Select
              </button>
            </template>
          </EasyDataTable>
        </div>
      </div>

      <!-- Product Column -->
      <div class="md:w-1/2">
        <div class="border rounded p-4 shadow-sm">
          <p class="font-semibold mb-3">Select Product</p>
          <input
            v-model="searchProduct"
            type="text"
            placeholder="Search Products..."
            class="w-full mb-4 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <EasyDataTable
            buttons-paginations
            alternating
            :items="productList"
            :headers="productHeaders"
            :rows-per-page="10"
          >
            <template #item-action="{ id }">
              <button
                @click="openQtyModal(id)"
                class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded"
              >
                Select
              </button>
            </template>
          </EasyDataTable>
        </div>
      </div>
    </div>

    <!-- Invoice Section -->
    <div class="mt-10 border rounded p-6 shadow-sm">
      <h5 class="text-right text-lg font-semibold mb-1">Invoice</h5>
      <h6 class="text-right text-sm text-gray-600 mb-4">{{ today }}</h6>

      <div class="mb-4">
        <h6 class="font-semibold mb-1">Work Order For:</h6>
        <p>Name: {{ customer.name }}</p>
        <p>Mobile: {{ customer.phone }}</p>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 table-auto">
          <thead class="bg-gray-100">
            <tr>
              <th class="border px-2 py-1 text-left text-sm font-semibold">No</th>
              <th class="border px-2 py-1 text-left text-sm font-semibold">Weight</th>
              <th class="border px-2 py-1 text-left text-sm font-semibold">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="selectedProductList.length" v-for="(product, index) in selectedProductList" :key="index">
              <td class="border px-2 py-1 text-xs">{{ index + 1 }}</td>
              <td class="border px-2 py-1 text-xs">{{ product.weight }}</td>
              <td class="border px-2 py-1 text-xs">
                <button
                  @click="removeProduct(index)"
                  class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs"
                >
                  Delete
                </button>
              </td>
            </tr>
            <tr v-else>
              <td colspan="3" class="text-center py-3 text-gray-600 text-sm">No product added yet</td>
            </tr>
          </tbody>
          <tfoot>
            <tr class="font-semibold">
              <td colspan="2" class="border px-2 py-1 text-sm text-right">Total</td>
              <td class="border px-2 py-1 text-sm">{{ totalWeight }}</td>
            </tr>
          </tfoot>
        </table>
      </div>

      <div class="mt-6 flex flex-col md:flex-row md:justify-between space-y-3 md:space-y-0">
        <button
          @click="createInvoice"
          class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2 rounded w-full md:w-auto"
        >
          Confirm
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from "vue";
import { usePage, useForm, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

const page = usePage();
const toaster = createToaster();

const showModal = ref(false);
const weight = ref(0);
const searchCustomer = ref("");
const searchProduct = ref("");

const customer = reactive({ name: "", phone: "", id: "" });
const selectedProduct = reactive({ id: "", available_unit: 0 });

const customerItem = ref(page.props.customer || []);
const productList = ref(page.props.sewingReceive || []);
const selectedProductList = ref([]);

const totalWeight = ref(0);

const customerHeaders = [
  { text: "No", value: "id" },
  { text: "Name", value: "name", sortable: true },
  { text: "Mobile", value: "phone" },
  { text: "Action", value: "action" },
];

const productHeaders = [
  { text: "No", value: "id" },
  { text: "Per Unit Cost", value: "per_unit_cost", sortable: true },
  { text: "Available Unit", value: "available_unit", sortable: true },
  { text: "Action", value: "action" },
];

const today = computed(() => new Date().toISOString().slice(0, 10));

function addCustomer(name, phone, id) {
  customer.name = name;
  customer.phone = phone;
  customer.id = id;
}

function openQtyModal(id) {
  const product = productList.value.find(p => p.id === id);
    selectedProduct.id = product.id;
    selectedProduct.available_unit = product.available_unit;
    weight.value = 0;
    showModal.value = true;

}

function closeModal() {
  showModal.value = false;
}

function addProduct() {

  const isExist = selectedProductList.value.find(p => p.id === selectedProduct.id);
  if (isExist) {
    return toaster.error("Product already added");
  }

  if (weight.value <= 0) {
    return toaster.error("Minimum quantity is 1");
  }
  if (weight.value > selectedProduct.available_unit) {
    return toaster.error("Quantity is not available");
  }




  selectedProductList.value.push({
    id: selectedProduct.id,
    weight: weight.value,
  });

  calculateTotal();
  closeModal();
}

function removeProduct(index) {
  selectedProductList.value.splice(index, 1);
  calculateTotal();
}

function calculateTotal() {
  totalWeight.value = selectedProductList.value.reduce((sum, item) => sum + parseFloat(item.weight), 0).toFixed(2);
}

const form = useForm({
  customer_id: "",
  products: [],
  total_weight: "",

});

function createInvoice() {
  if (!customer.name) return toaster.error("Customer is required");
  if (selectedProductList.value.length === 0) return toaster.error("Product is required");

  form.customer_id = customer.id;
  form.products = selectedProductList.value;
  form.total_weight = totalWeight.value;

  form.post("/create-invoice", {
    onSuccess: () => {
      if (page.props.flash.status === true) {
        form.reset();
        selectedProductList.value = [];
        totalWeight.value = 0;
        toaster.success(page.props.flash.message);
        setTimeout(() => router.get("/invoice-list"), 500);
      } else {
        toaster.error(page.props.flash.error);
      }
    },
  });
}
</script>
