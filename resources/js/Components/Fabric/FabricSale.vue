<template>
    <!-- Quantity Modal -->
    <div
        v-if="showModal"
        class="fixed inset-0 z-50 flex items-start justify-center overflow-auto bg-black/15 bg-opacity-40 pt-20"
    >
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
            <!-- Modal Header -->
            <div
                class="flex justify-between items-center px-4 py-2 rounded-t-lg"
            >
                <h1 class="text-xl font-bold text-black">Add Quantity</h1>
                <button
                    class="text-white text-2xl font-bold bg-red-500 hover:bg-red-600 rounded-sm w-8 h-8 flex items-center justify-center"
                    @click="closeModal"
                >
                    &times;
                </button>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label for="available_weight" class="block font-semibold mb-1"
                        >Available Weight</label
                    >
                    <input
                        v-model="selectedFabrics.available_weight"
                        type="text"
                        class="w-full border px-3 py-2 rounded"
                        readonly
                    />
                </div>

                <div>
                    <label for="weight" class="block font-semibold mb-1"
                        >Weight</label
                    >
                    <input
                        v-model="weight"
                        type="text"
                        class="w-full border px-3 py-2 rounded"
                    />
                </div>

                    <div>
                    <label for="available_roll" class="block font-semibold mb-1"
                        >Available Roll</label
                    >
                    <input
                        v-model="selectedFabrics.roll"
                        type="text"
                        class="w-full border px-3 py-2 rounded"
                        readonly
                    />
                </div>

                <div>
                    <label for="roll" class="block font-semibold mb-1"
                        >Roll</label
                    >
                    <input
                        v-model="roll"
                        type="text"
                        class="w-full border px-3 py-2 rounded"
                    />
                </div>

                <div>
                    <label for="sale_price" class="block font-semibold mb-1"
                        >Cost Price</label
                    >
                    <input
                        v-model="selectedFabrics.per_unit_cost"
                        type="text"
                        class="w-full border px-3 py-2 rounded"
                        readonly
                    />
                </div>

                    <div>
                    <label for="sale_price" class="block font-semibold mb-1"
                        >Sale Price</label
                    >
                    <input
                        v-model="price"
                        type="text"
                        class="w-full border px-3 py-2 rounded"
                    />
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end px-6 py-3">
                <button
                    @click="addFabrics"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded"
                >
                    Add
                </button>
            </div>
        </div>
    </div>

    <!-- Main Page -->
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Fabric Sale</h1>

        <div class="flex flex-col md:flex-row md:space-x-6">
            <!-- Customer Selection -->
            <div class="md:w-1/2 mb-6 md:mb-0">
                <div class="border rounded p-4 shadow-sm">
                    <p class="font-semibold mb-3">Select Customer</p>
                    <input
                        v-model="searchCustomer"
                        type="text"
                        placeholder="Search Party..."
                        class="w-full mb-4 px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500"
                    />
                    <EasyDataTable
                        buttons-paginations
                        alternating
                        :items="customerList"
                        :headers="customerHeaders"
                        :rows-per-page="10"
                    >
                        <template
                            #item-action="{ name, phone, id, due_amount }"
                        >
                            <button
                                @click="
                                    addCustomer(name, phone, id, due_amount)
                                "
                                class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded"
                            >
                                Select
                            </button>
                        </template>
                    </EasyDataTable>
                </div>
            </div>

            <!-- Product Selection -->
            <div class="md:w-1/2">
                <div class="border rounded p-4 shadow-sm">
                    <p class="font-semibold mb-3">Select Product</p>
                    <input
                        v-model="searchProduct"
                        type="text"
                        placeholder="Search Products..."
                        class="w-full mb-4 px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500"
                    />
                    <EasyDataTable
                        buttons-paginations
                        alternating
                        :items="fabricList"
                        :headers="fabricHeaders"
                        :rows-per-page="10"
                    >
                        <template
                            #item-action="{
                                id,
                                per_unit_cost,
                                available_unit,
                                dyeing,
                                roll,

                            }"
                        >
                            <button
                                @click="
                                    openQtyModal(
                                        id,
                                        per_unit_cost,
                                        available_unit,
                                        dyeing,
                                        roll,

                                    )
                                "
                                class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded"
                            >
                                Select
                            </button>
                        </template>
                    </EasyDataTable>
                </div>
            </div>
        </div>

        <!-- Invoice Preview -->
        <div class="mt-10 border rounded p-6 shadow-sm">
            <h5 class="text-right text-lg font-semibold mb-1">Invoice</h5>
            <h6 class="text-right text-sm text-gray-600 mb-4">
                <span class="text-right text-lg font-semibold m-1">Date:</span>
                <input
                    v-model="form.sale_date"
                    class="border px-3 py-2 rounded-sm"
                    type="date"
                />
            </h6>
            <h6 class="text-right text-sm text-gray-600 mb-4">
                <span class="text-right text-lg font-semibold m-1"
                    >Challan no:</span
                >
                <input
                    v-model="form.challan_no"
                    class="border px-3 py-2 rounded-sm"
                    type="text"
                />
            </h6>

            <!-- Customer Info -->
            <div class="mb-4">
                <h6 class="font-semibold mb-1">Fabric Sale For:</h6>
                <p>Name: {{ customer.name }}</p>
                <p>Mobile: {{ customer.phone }}</p>
                <p>Due Amount: {{ customer.due_amount }}</p>
            </div>

            <!-- Invoice Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 table-auto">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1 text-sm">No</th>
                            <th class="border px-2 py-1 text-sm">Design Name</th>
                            <th class="border px-2 py-1 text-sm">Color</th>
                            <th class="border px-2 py-1 text-sm">Weight</th>
                            <th class="border px-2 py-1 text-sm">
                                Price
                            </th>
                            <th class="border px-2 py-1 text-sm">Total</th>
                            <th class="border px-2 py-1 text-sm">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-if="fabrics.length > 0"
                            v-for="(fabric, index) in fabrics"
                            :key="index"
                            class="odd:bg-gray-50"
                        >
                            <td class="border px-2 py-1 text-xs">
                                {{ index + 1 }}
                            </td>
                            <td class="border px-2 py-1 text-xs">{{ fabric.design_name }}</td>
                            <td class="border px-2 py-1 text-xs">{{ fabric.color }}</td>
                            <td class="border px-2 py-1 text-xs">
                                {{ fabric.weight }}
                            </td>
                            <td class="border px-2 py-1 text-xs">
                                {{ fabric.price }}
                            </td>
                            <td class="border px-2 py-1 text-xs">
                                {{ fabric.total_amount }}
                            </td>
                            <td class="border px-2 py-1 text-xs">
                                <button
                                    @click="removeFabrics(index)"
                                    class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <tr v-else>
                            <td
                                colspan="6"
                                class="text-center py-3 text-sm text-gray-600"
                            >
                                No product added yet
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="font-semibold">
                            <td
                                colspan="6"
                                class="border px-2 py-1 text-sm text-right"
                            >
                                Total
                            </td>
                            <td class="border px-2 py-1 text-sm">
                                {{ totalAmount }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Submit Invoice Button -->
            <div class="mt-6 flex justify-end">
                <button
                    @click="createInvoice"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2 rounded"
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

// Basic setup
const page = usePage();
const toaster = createToaster({});

// Modal state
const showModal = ref(false);
const weight = ref(0);
const price = ref(0);
const roll = ref(0);

// State for selected fabric
const selectedFabrics = reactive({
    id: "",
    per_unit_cost: 0,
    available_weight: 0,
    roll: 0,
    design_name: "",
    color: "",
});

// Customer & fabric search
const searchCustomer = ref("");
const searchProduct = ref("");

// Data from server
const customerList = ref(page.props.customers || []);
const fabricList = ref(page.props.fabrics || []);

// DataTable headers
const customerHeaders = [
    { text: "No", value: "id" },
    { text: "Name", value: "name", sortable: true },
    { text: "Mobile", value: "phone" },
    { text: "Action", value: "action" },
];

const fabricHeaders = [
    { text: "No", value: "id" },
    { text: "Desing Name", value: "dyeing.design_name" },
    { text: "Color", value: "dyeing.color" },
    { text: "Per Unit Cost", value: "per_unit_cost" },
    { text: "Available Unit", value: "available_unit" },
    { text: "Roll", value: "roll" },
    { text: "Action", value: "action" },
];

// Selected customer info
const customer = reactive({ name: "", phone: "", id: "", due_amount: 0 });

// Selected fabric list
const fabrics = ref([]);

// Add customer from table
function addCustomer(name, phone, id, due_amount) {
    customer.name = name;
    customer.phone = phone;
    customer.id = id;
    customer.due_amount = due_amount;
}

// Open quantity modal
function openQtyModal(id, per_unit_cost, available_unit, dyeing,roll) {
    selectedFabrics.id = id;
    selectedFabrics.per_unit_cost = per_unit_cost;
    selectedFabrics.available_weight = available_unit;
    selectedFabrics.design_name = dyeing.design_name;
    selectedFabrics.color = dyeing.color;
    selectedFabrics.roll = roll;
    showModal.value = true;
}

// Close modal
function closeModal() {
    showModal.value = false;
}

// Add selected fabric to invoice
function addFabrics() {
    if (fabrics.value.find((f) => f.id === selectedFabrics.id)) {
        toaster.error("Fabric already added");
        return;
    }

    if (price.value <= 0) {
        toaster.error("Please enter a sale price greater than zero.");
        return;
    }

    if (weight.value > parseFloat(selectedFabrics.available_weight)) {
        toaster.error("Quantity is not available");

        return;
    }

    fabrics.value.push({
        id: selectedFabrics.id,
        weight: weight.value,
        price: price.value,
        design_name: selectedFabrics.design_name,
        roll: selectedFabrics.roll,
        color: selectedFabrics.color,
        total_amount: (
            parseFloat(price.value) * weight.value
        ).toFixed(2),
        per_unit_cost: selectedFabrics.per_unit_cost,
    });

    closeModal();
}

// Remove fabric row
function removeFabrics(index) {
    fabrics.value.splice(index, 1);
}

// Calculate total
const totalAmount = computed(() =>
 {
    return fabrics.value.reduce((sum, fabric) => sum + parseFloat(fabric.total_amount), 0);
});

const totalUnit = computed(() => {
    return fabrics.value.reduce((sum, fabric) => sum + parseFloat(fabric.weight), 0);
})

const totalCost = computed(() => {
    return fabrics.value.reduce((sum, fabric) => sum + parseFloat(fabric.weight * fabric.per_unit_cost), 0);
})

// Form and invoice submission
const form = useForm({
    customer_id: "",
    fabrics: [],
    total_unit: "",
    total_amount: "",
    sale_date: "",
    challan_no: "",
    total_cost: ""
});

function createInvoice() {
    if (!form.sale_date) return toaster.error("Date is required");

    if (!form.challan_no) return toaster.error("Challan No is required");

    if (!customer.name) return toaster.error("Customer is required");

    if (!fabrics.value.length) return toaster.error("Fabric is required");

    form.customer_id = customer.id;
    form.fabrics = fabrics.value;
    form.total_unit = totalUnit.value;
    form.total_amount = totalAmount.value;
    form.total_cost = totalCost.value

    form.post("fabric-sale", {
        onSuccess: () => {
            if (page.props.flash.status === true) {
                form.reset();
                fabrics.value = [];
                totalAmount.value = 0;
                totalUnit.value = 0;
                form.total_cost = 0
                toaster.success(page.props.flash.message);
                setTimeout(() => router.get("/fabric-list"), 500);
            } else {
                toaster.error(page.props.flash.message);
            }
        },
    });
}
</script>
