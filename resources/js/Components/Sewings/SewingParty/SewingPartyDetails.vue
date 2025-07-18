<script setup>

const props = defineProps({
    selectedParty: Object,
    modal: Boolean,
    sewingPayment: Object,
});

// Emit event to update modal visibility
const emit = defineEmits(["update:modal"]);

// Print the modal content
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
        <div
            id="modal-content"
            class="bg-white w-[1100px] max-w-full rounded-xl shadow-2xl p-6 relative print:w-full print:shadow-none print:p-0 h-[500px] overflow-auto"
        >
            <!-- Close Button -->
            <button
                @click="$emit('update:modal', false)"
                class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-2xl print:hidden"
            >
                &times;
            </button>

            <!-- Print Button -->
            <button
                @click="printModal"
                class="absolute top-3 left-3 text-sm bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition print:hidden"
            >
                🖨️ Print
            </button>

            <!-- Party Information -->
            <h1 class="text-2xl font-bold pb-2">
                Party Name: {{ props.selectedParty[0].sewing_party.name || "-" }}
            </h1>

            <!-- Sewing Data Table -->
            <div class="overflow-x-auto">
                <table
                    v-for="(sewing, index) in props.selectedParty || []"
                    :key="index"
                    class="w-full border border-gray-300 text-sm mt-4"
                >
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border text-center">No</th>
                            <th class="px-4 py-2 border text-center">Pcs</th>
                            <th class="px-4 py-2 border text-center">
                                Available Pcs
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border text-center">
                                {{ index + 1 }}
                            </td>
                              <td class="px-4 py-2 border text-center">
                                {{ sewing.unit }}
                            </td>
                            <td class="px-4 py-2 border text-center">
                                {{ sewing.available_unit }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Payment Summary -->
                <div class="mt-4 space-y-1 font-bold">
                    <p>Total Due: {{ props.selectedParty[0].sewing_party.due_amount || 0 }}</p>
                    <p>
                        Last Paid Amount: {{ props.sewingPayment?.amount || 0 }}
                    </p>
                    <p>
                        Last Paid Date:
                        {{ props.sewingPayment?.created_at || "-" }}
                    </p>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="mt-6 text-center text-xs text-gray-500 print:hidden">
                Press the Print button or Ctrl+P to print this summary.
            </div>
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

    table {
        width: 100% !important;
        border-collapse: collapse !important;
        page-break-inside: auto;
    }

    thead {
        display: table-header-group;
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
}
</style>
