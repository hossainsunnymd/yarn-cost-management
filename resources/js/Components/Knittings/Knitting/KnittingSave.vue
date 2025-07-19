<template>
    <!-- Quantity Modal -->
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
            <!-- Modal Header -->
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

            <!-- Modal Body -->
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label for="Weight" class="block font-semibold mb-1">Weight</label>
                    <input
                        v-model="weight"
                        type="text"
                        class="w-full border px-3 py-2 rounded"
                    />
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end px-6 py-3 rounded-b-lg">
                <button
                    @click="addYarns"
                    type="button"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded"
                >
                    Add
                </button>
            </div>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Add Knitting</h1>

        <div class="flex flex-col md:flex-row md:space-x-6">
            <!-- Knitting Party Selection -->
            <div class="md:w-1/2 mb-6 md:mb-0">
                <div class="border rounded p-4 shadow-sm">
                    <p class="font-semibold mb-3">Select Knitting Party</p>
                    <input
                        v-model="searchParty"
                        type="text"
                        placeholder="Search Party..."
                        class="w-full mb-4 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <EasyDataTable
                        buttons-paginations
                        alternating
                        :items="knittingPartyList"
                        :headers="knittingPartyHeaders"
                        :rows-per-page="10"
                    >
                        <template #item-action="{ name, phone, id }">
                            <button
                                @click="addYarnParty(name, phone, id)"
                                class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded"
                            >
                                Select
                            </button>
                        </template>
                    </EasyDataTable>
                </div>
            </div>

            <!-- Yarn Selection -->
            <div class="md:w-1/2">
                <div class="border rounded p-4 shadow-sm">
                    <p class="font-semibold mb-3">Select Yarns</p>
                    <input
                        v-model="searchYarn"
                        type="text"
                        placeholder="Search Yarns..."
                        class="w-full mb-4 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <EasyDataTable
                        buttons-paginations
                        alternating
                        :items="yarnPurchaseList"
                        :headers="yarnPurchaseHeaders"
                        :rows-per-page="10"
                    >
                        <template #item-action="{ id, per_unit_cost, available_unit }">
                            <button
                                @click="openQtyModal(id, per_unit_cost, available_unit)"
                                class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded"
                            >
                                Select
                            </button>
                        </template>
                    </EasyDataTable>
                </div>
            </div>
        </div>

        <!-- Invoice Summary -->
        <div class="mt-10 border rounded p-6 shadow-sm">
            <h5 class="text-right text-lg font-semibold mb-1">Invoice</h5>
            <h6 class="text-right text-sm text-gray-600 mb-4">
                {{ new Date().toISOString().slice(0, 10) }}
            </h6>

            <!-- Selected Party -->
            <div class="mb-4">
                <h6 class="font-semibold mb-1">Knitting For:</h6>
                <p>Name: {{ yarnParty.name }}</p>
                <p>Mobile: {{ yarnParty.phone }}</p>
            </div>

            <!-- Fabir Name -->
             
            <div class="mb-4">
                <label for="fabricName" class="block font-semibold mb-1">Fabric Name</label>
                <input
                    v-model="form.fabric_name"
                    type="text"
                    id="fabricName"
                    class="w-full border px-3 py-2 rounded"
                />
            </div>


            <!-- Yarn Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 table-auto">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1 text-left text-sm font-semibold">No</th>
                            <th class="border px-2 py-1 text-left text-sm font-semibold">Weight</th>
                            <th class="border px-2 py-1 text-left text-sm font-semibold">Per Unit Cost</th>
                            <th class="border px-2 py-1 text-left text-sm font-semibold">Total</th>
                            <th class="border px-2 py-1 text-left text-sm font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-if="yarnList.length > 0"
                            v-for="(yarn, index) in yarnList"
                            :key="index"
                            class="odd:bg-gray-50"
                        >
                            <td class="border px-2 py-1 text-xs">{{ index + 1 }}</td>
                            <td class="border px-2 py-1 text-xs">{{ yarn.weight }}</td>
                            <td class="border px-2 py-1 text-xs">{{ yarn.per_unit_cost }}</td>
                            <td class="border px-2 py-1 text-xs">{{ yarn.total_cost }}</td>
                            <td class="border px-2 py-1 text-xs">
                                <button
                                    @click="removeYarns(index)"
                                    class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <tr v-else>
                            <td colspan="5" class="text-center py-3 text-gray-600 text-sm">
                                No product added yet
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="font-semibold">
                            <td colspan="3" class="border px-2 py-1 text-sm text-right">Total</td>
                            <td colspan="2" class="border px-2 py-1 text-sm">{{ calculate.total }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Confirm Button -->
            <div class="mt-6 flex flex-col 2xl:flex-row 2xl:justify-between space-y-3 2xl:space-y-0">
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
// Imports
import { ref, reactive } from "vue";
import { usePage, useForm, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";

// UI State
const page = usePage();
const toaster = createToaster();

// Modal
const showModal = ref(false);
const weight = ref("");

// Search Inputs
const searchParty = ref("");
const searchYarn = ref("");

// Selected Yarn
const selectedYarns = reactive({
    id: "",
    per_unit_cost: 0,
    weight: "",
    available_weight: 0,
});

// Props Data
const knittingPartyList = ref(page.props.knittingPartyList || []);
const yarnPurchaseList = ref(page.props.yarnPurchaseList || []);


// Table Headers
const knittingPartyHeaders = [
    { text: "No", value: "id" },
    { text: "Name", value: "name", sortable: true },
    { text: "Mobile", value: "phone" },
    { text: "Action", value: "action" },
];

const yarnPurchaseHeaders = [
    { text: "No", value: "id" },
    { text: "Name", value: "name" },
    { text: "Description", value: "description" },
    { text: "Per Unit Cost", value: "per_unit_cost", sortable: true },
    { text: "Available Unit", value: "available_unit", sortable: true },
    { text: "Action", value: "action" },
];

// Selected Party & Yarn List
const yarnParty = reactive({ name: "", phone: "", id: "" });
const yarnList = ref([]);

// Assign Party
function addYarnParty(name, phone, id) {
    yarnParty.name = name;
    yarnParty.phone = phone;
    yarnParty.id = id;
}

// Open Modal
function openQtyModal(id, per_unit_cost, available_unit) {
    selectedYarns.id = id;
    selectedYarns.per_unit_cost = per_unit_cost;
    selectedYarns.available_weight = available_unit;
    weight.value = 0;
    showModal.value = true;
}

// Close Modal
function closeModal() {
    showModal.value = false;
}

// Add Yarn Entry
function addYarns() {
    const ifExist = yarnList.value.find((yarn) => yarn.id === selectedYarns.id);
    if (ifExist) return toaster.error("Yarn already added");
    if (weight.value <= 0) return toaster.error("Enter quantity greater than zero.");
    if (parseFloat(weight.value) > parseFloat(selectedYarns.available_weight)) {
        return toaster.error("Only " + selectedYarns.available_weight + " available.");
    }

    yarnList.value.push({
        id: selectedYarns.id,
        per_unit_cost: selectedYarns.per_unit_cost,
        weight: weight.value,
        total_cost: (
            parseFloat(selectedYarns.per_unit_cost) * parseFloat(weight.value)
        ).toFixed(2),
    });

    closeModal();
    calculateTotal();
}

// Remove Yarn
function removeYarns(index) {
    yarnList.value.splice(index, 1);
    calculateTotal();
}

// Total Calculation
const calculate = reactive({ total: 0, weight: 0 });

function calculateTotal() {
    calculate.total = 0;
    calculate.weight = 0;
    yarnList.value.forEach((yarn) => {
        calculate.total += parseFloat(yarn.per_unit_cost * yarn.weight);
        calculate.weight += parseFloat(yarn.weight);
    });
    calculate.total = calculate.total.toFixed(2);
}

// Form Submission
const form = useForm({
    knitting_party_id: "",
    yarns: [],
    total: "",
    total_weight: "",
    fabric_name: "",
});

function createInvoice() {
    if (!yarnParty.name) return toaster.error("Yarn Party is required");
    if (yarnList.value.length === 0) return toaster.error("Yarn is required");
    
    if (form.fabric_name === "") return toaster.error("Fabric name is required");


    form.knitting_party_id = yarnParty.id;
    form.yarns = yarnList.value;
    form.total = calculate.total;
    form.total_weight = calculate.weight;

    form.post("create-knitting", {
        onSuccess: () => {
            if (page.props.flash.status === true) {
                form.reset();
                yarnList.value = [];
                calculate.total = 0;
                toaster.success(page.props.flash.message);
                setTimeout(() => router.get("/knitting-list"), 500);
            } else {
                toaster.error(page.props.flash.error);
            }
        },
    });
}
</script>
