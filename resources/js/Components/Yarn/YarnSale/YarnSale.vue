<template>
    <!-- Modal -->
    <div
        v-if="showModal"
        class="fixed inset-0 z-50 flex items-start justify-center overflow-auto bg-black/40 pt-20"
    >
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
            <!-- Header -->
            <div class="flex justify-between items-center px-4 py-2">
                <h1 class="text-xl font-bold">Add Yarn</h1>
                <button
                    class="bg-red-500 text-white w-8 h-8 rounded"
                    @click="closeModal"
                >
                    ×
                </button>
            </div>

            <!-- Body -->
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block font-semibold mb-1"
                        >Available Weight</label
                    >
                    <input
                        v-model="selectedYarn.available_unit"
                        type="text"
                        class="w-full border px-3 py-2 rounded"
                        readonly
                    />
                </div>

                <div>
                    <label class="block font-semibold mb-1">Weight</label>
                    <input
                        v-model="weight"
                        type="number"
                        class="w-full border px-3 py-2 rounded"
                        min="0"
                    />
                </div>

                <div>
                    <label class="block font-semibold mb-1"
                        >Cost per unit</label
                    >
                    <input
                        v-model="selectedYarn.per_unit_cost"
                        type="text"
                        class="w-full border px-3 py-2 rounded"
                        readonly
                    />
                </div>

                <div>
                    <label class="block font-semibold mb-1"
                        >Sale Price (per unit)</label
                    >
                    <input
                        v-model="price"
                        type="number"
                        min="0"
                        class="w-full border px-3 py-2 rounded"
                    />
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end px-6 py-3">
                <button
                    @click="addYarn"
                    class="bg-green-600 text-white px-4 py-2 rounded"
                >
                    Add
                </button>
            </div>
        </div>
    </div>

    <!-- Page -->
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Create Sale</h1>

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Customer -->
            <div class="border p-4 rounded shadow">
                <p class="font-semibold mb-3">Select Customer</p>

                <EasyDataTable :headers="customerHeaders" :items="customers">
                    <template #item-action="{ name, phone, id, due_amount }">
                        <button
                            @click="addCustomer(name, phone, id, due_amount)"
                            class="bg-green-600 text-white px-3 py-1 text-xs rounded"
                        >
                            Select
                        </button>
                    </template>
                </EasyDataTable>
            </div>

            <!-- Yarn -->
            <div class="border p-4 rounded shadow">
                <p class="font-semibold mb-3">Select Yarn</p>

                <EasyDataTable :headers="yarnHeaders" :items="yarnList">
                    <template #item-action="{ id }">
                        <button
                            @click="openQtyModal(id)"
                            class="bg-green-600 text-white px-3 py-1 text-xs rounded"
                        >
                            Select
                        </button>
                    </template>
                </EasyDataTable>
            </div>
        </div>

        <!-- Invoice -->
        <div class="mt-10 border rounded p-6 shadow">
            <h5 class="text-right font-semibold">Invoice</h5>

            <div class="flex justify-end gap-4 mb-4">
                <input
                    v-model="form.sale_date"
                    type="date"
                    class="border px-2"
                />
                <input
                    v-model="form.challan_no"
                    placeholder="Challan No"
                    class="border px-2"
                />
            </div>

            <div class="mb-4">
                <p><b>Name:</b> {{ customer.name }}</p>
                <p><b>Mobile:</b> {{ customer.phone }}</p>
                <p><b>Due:</b> {{ customer.due_amount }}</p>
            </div>

            <table class="w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-1">#</th>
                        <th class="border p-1">Yarn</th>
                        <th class="border p-1">Weight</th>
                        <th class="border p-1">Price</th>
                        <th class="border p-1">Sale Amount</th>
                        <th class="border p-1">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(yarn, index) in selectedYarnList" :key="index">
                        <td class="border p-1">{{ index + 1 }}</td>
                        <td class="border p-1">{{ yarn.name }}</td>
                        <td class="border p-1">{{ yarn.weight }}</td>
                        <td class="border p-1">{{ yarn.price }}</td>
                        <td class="border p-1">{{ yarn.sale_price }}</td>
                        <td class="border p-1">
                            <button
                                @click="removeYarn(index)"
                                class="bg-red-600 text-white px-2 py-1 text-xs rounded"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>

                    <tr v-if="!selectedYarnList.length">
                        <td colspan="5" class="text-center p-3 text-gray-500">
                            No yarn added
                        </td>
                    </tr>
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right font-semibold p-1">
                            Total
                        </td>
                        <td class="border p-1 font-semibold">
                            {{ calculateTotal }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-4 text-right">
                <button
                    @click="createYarnSale"
                    class="bg-green-600 text-white px-5 py-2 rounded"
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
const price = ref(0);

const customers = ref(page.props.customers || []);
const yarnList = ref(page.props.yarns || []);

const customer = reactive({
    name: "",
    phone: "",
    id: "",
    due_amount: 0,
});

const selectedYarn = reactive({
    id: "",
    name: "",
    available_unit: 0,
    per_unit_cost: 0,
});

const selectedYarnList = ref([]);

const today = computed(() => new Date().toISOString().slice(0, 10));

const customerHeaders = [
    { text: "Name", value: "name" },
    { text: "Mobile", value: "phone" },
    { text: "Action", value: "action" },
];

const yarnHeaders = [
    { text: "Name", value: "name" },
    { text: "Stock", value: "available_unit" },
    { text: "Unit Price", value: "per_unit_cost" },
    { text: "Action", value: "action" },
];

function addCustomer(name, phone, id, due_amount) {
    customer.name = name;
    customer.phone = phone;
    customer.id = id;
    customer.due_amount = due_amount;
}

function openQtyModal(id) {
    const yarn = yarnList.value.find((y) => y.id === id);
    if (!yarn) return;

    selectedYarn.id = yarn.id;
    selectedYarn.name = yarn.description;
    selectedYarn.available_unit = yarn.available_unit;
    selectedYarn.per_unit_cost = yarn.per_unit_cost;

    showModal.value = true;
}

function closeModal() {
    weight.value = 0;
    price.value = 0;
    showModal.value = false;
}

function addYarn() {
    const isExist = selectedYarnList.value.find(
        (yarn) => yarn.id === selectedYarn.id
    );

    if (weight.value <= 0) return toaster.error("Enter quantity greater than 0");

    if (price.value <= 0) return toaster.error("Enter price greater than 0");

    if (isExist) return toaster.error("Yarn already added");

    if (weight.value > selectedYarn.available_unit)
        return toaster.error("Stock not available");

    selectedYarnList.value.push({
        id: selectedYarn.id,
        name: selectedYarn.name,
        weight: weight.value,
        price: price.value,
        sale_price: weight.value * price.value,
    });
    closeModal();
}

function removeYarn(index) {
    selectedYarnList.value.splice(index, 1);
}

const calculateTotal=computed(
    () =>  {
        return selectedYarnList.value.reduce(
            (sum, item) => sum + Number(item.sale_price),
            0
        );
    }
);

const calculateTotalWeight=computed(
    () => {
       return selectedYarnList.value.reduce(
            (sum, item) => sum + Number(item.weight),
            0
        );
    }
);

const form = useForm({
    customer_id: "",
    yarns: [],
    total_amount: "",
    sale_date: "",
    total_unit: "",
    challan_no: "",
});

function createYarnSale() {
    if(!form.challan_no) return toaster.error("Challan No is required");
    if(!form.sale_date) return toaster.error("Date is required");
    if (!customer.id) return toaster.error("Customer required");
    if (!selectedYarnList.value.length) return toaster.error("Add yarn");

    form.customer_id = customer.id;
    form.yarns = selectedYarnList.value;
    form.total_amount = calculateTotal.value;
    form.total_unit = calculateTotalWeight.value;

    form.post("/create-yarn-sale", {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status === true) {
                form.reset();
                selectedYarnList.value = [];
                calculateTotal.value = 0;
                calculateTotalWeight.value = 0;
                toaster.success(page.props.flash.message);
                setTimeout(() => router.get("/yarn-sale-list"), 500);
            } else if(page.props.flash.status === false) {
                toaster.error(page.props.flash.message);
            }
        },
    });
}
</script>
