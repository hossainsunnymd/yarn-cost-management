<script setup>
// Props: Receives fabric product data and modal state from parent
const props = defineProps({
    fabricProducts: Object,
    modal: Boolean,
});

// Emit event to close the modal
const emit = defineEmits(["update:modal"]);

// Handles print functionality
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
    <!-- Modal Wrapper -->
    <div
        v-if="modal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/20"
    >
        <!-- Modal Content -->
        <div
            id="modal-content"
            class="bg-white w-[1100px] max-w-full rounded-xl shadow-2xl p-6 relative print:w-full print:shadow-none print:p-0 h-[500px] overflow-auto"
        >
            <!-- Close Button (Hidden on print) -->
            <button
                @click="$emit('update:modal', false)"
                class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-2xl print:hidden"
            >
                &times;
            </button>

            <!-- headers -->
            <div class="flex justify-center items-center">
                <h1 class="text-2xl font-bold text-left pb-2">
                    Fabric Sale Details
                </h1>
            </div>

            <div>
                <h1 class="text-2xl font-bold mb-10">
                    Challan No: {{ props.fabricProducts.challan_no }}
                </h1>
                <h1 class="font-bold">
                    Date:
                    {{ new Date(props.fabricProducts.sale_date).toLocaleDateString() }}
                </h1>
                <h1 class="font-bold">
                    Customer Name: {{ props.fabricProducts.customer.name }}
                </h1>
                <h1 class="font-bold">
                    Customer Phone: {{ props.fabricProducts.customer.phone }}
                </h1>
            </div>

            <!-- Fabric Sale Product Table -->
            <div class="overflow-x-auto mt-20">
                <table
                    class="w-full border border-gray-300 text-sm border-collapse"
                >
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border text-center">#</th>
                            <th class="px-4 py-2 border text-center">
                                Desing Name
                            </th>
                            <th class="px-4 py-2 border text-center">Unit</th>
                            <th class="px-4 py-2 border text-center">Price</th>
                            <th class="px-4 py-2 border text-center">
                                Total Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(item, index) in props.fabricProducts
                                .fabric_sale_products"
                            :key="index"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-4 py-2 border text-center">
                                {{ index + 1 }}
                            </td>
                            <td class="px-4 py-2 border text-center">
                                {{ item.dyeing_receive.dyeing.design_name }}
                            </td>
                            <td class="px-4 py-2 border text-center">
                                {{ item.unit }}
                            </td>

                            <td class="px-4 py-2 border text-center">
                                {{ item.price }}
                            </td>

                            <td class="px-4 py-2 border text-center">
                                {{ item.total_amount }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Print Info Note (Hidden on print) -->
            <div class="mt-6 text-center text-xs text-gray-500 print:hidden">
                Press the Print button or Ctrl+P to print this summary.
            </div>

            <!-- Print button -->
            <button
                @click="printModal"
                class="text-sm bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition print:hidden"
                aria-label="Print modal content"
            >
                🖨️ Print
            </button>
        </div>
    </div>
</template>

<style scoped>
/* Print Styles */
@media print {
    th,
    td {
        border: 1px solid black !important;
        color: black !important;
    }

    th {
        font-weight: bold !important;
    }

    td {
        font-weight: normal !important;
    }

    table {
        width: 100% !important;
        border-collapse: collapse !important;
        page-break-inside: auto;
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
