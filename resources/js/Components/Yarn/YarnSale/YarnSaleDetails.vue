<script setup>
const props = defineProps({
    yarns: Object,
    modal: Boolean,
});

// Emit event to update modal visibility
const emit = defineEmits(["update:modal"]);

// Function to print only the modal content
const printModal = () => {
    const printContents = document.getElementById("modal-content").innerHTML;
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();

    document.body.innerHTML = originalContents;
    location.reload();
};
</script>

<template>
    <!-- Modal wrapper: shows only if modal prop is true -->
    <div
        v-if="modal"
        class="fixed inset-0 z-50 flex items-center justify-center"
    >
        <div
            id="modal-content"
            class="bg-white w-[1100px] max-w-full rounded-xl shadow-2xl p-6 relative print:w-full print:shadow-none print:p-0 h-[500px] overflow-auto"
        >
            <!-- Close button -->
            <button
                @click="$emit('update:modal', false)"
                class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-2xl print:hidden"
                aria-label="Close modal"
            >
                &times;
            </button>

            <!-- headers -->
            <div class="flex justify-center items-center">
                <h1 class="text-2xl font-bold text-left pb-2">
                    Yarn Sale Details
                </h1>
            </div>

            <div>
                <h1 class="text-2xl font-bold mb-10">Challan No: {{ props.yarns.challan_no }}</h1>
                <h1 class="font-bold">Date: {{ new Date(props.yarns.sale_date).toLocaleDateString() }}</h1>
                <h1 class="font-bold">Customer Name: {{ props.yarns.customer.name }}</h1>
                <h1 class="font-bold">Customer Phone: {{ props.yarns.customer.phone }}</h1>
            </div>

            <!-- Products Table -->
            <div class="overflow-x-auto overflow-y-auto mt-20">
                <table class="w-full border border-gray-300 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border text-center">#</th>
                            <th class="px-4 py-2 border text-center">
                                Price
                            </th>
                            <th class="px-4 py-2 border text-center">Unit</th>
                            <th class="px-4 py-2 border text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(item, index) in props.yarns
                                .yarn_sale_products"
                            :key="index"
                            class="hover:bg-gray-50 border-1 print:border-2"
                        >
                            <td class="px-4 py-2 border text-center">
                                {{ index + 1 }}
                            </td>
                            <td class="px-4 py-2 border text-center">
                                {{ item.price }}
                            </td>
                            <td class="px-4 py-2 border text-center">
                                {{ item.unit }}
                            </td>
                            <td class="px-4 py-2 border text-center">
                                {{ item.total_amount }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer note shown only on screen -->
            <div class="mt-6 text-center text-xs text-gray-500 print:hidden">
                Press the Print button or Ctrl+P to print this summary.
            </div>

                  <!-- Print button -->
            <button
                @click="printModal"
                class=" text-sm bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition print:hidden"
                aria-label="Print modal content"
            >
                🖨️ Print
            </button>
        </div>
    </div>
</template>

<style scoped>
/* Print styles to ensure clean printed output */
@media print {
    th {
        border: 1px solid black !important;
        font-weight: bold !important;
        color: black !important;
    }

    td {
        border: 1px solid black !important;
        font-weight: normal !important;
        color: black !important;
    }

    table {
        page-break-inside: auto;
        width: 100% !important;
        border-collapse: collapse !important;
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

    thead {
        display: table-header-group;
    }
}
</style>
