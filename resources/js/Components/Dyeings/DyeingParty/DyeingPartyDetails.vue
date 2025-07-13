<script setup>
// Props: receives items, modal state, and dyeing payment info
const props = defineProps({
  selectedParty: Array,
  modal: Boolean,
  dyeingPayment: Object,
});

// Emits an event to close the modal
const emit = defineEmits(["update:modal"]);

// Print handler: prints modal content
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
  <div v-if="modal" class="fixed inset-0 z-50 flex items-center justify-center">
    <!-- Modal Content -->
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

      <!-- Party Name Title -->
      <h1 class="text-2xl font-bold text-left pb-2">
        Party Name: {{ props.selectedParty.name }}
      </h1>

      <!-- Table Section -->
      <div class="overflow-x-auto overflow-y-auto">
        <!-- Loop through each dyeing item -->
        <table
          v-for="(dyeing, i) in props.selectedParty.dryings"
          :key="i"
          class="w-full border border-gray-300 text-sm mt-4"
        >
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 border text-center">Weight</th>
              <th class="px-4 py-2 border text-center">Available Weight</th>
            </tr>
          </thead>
          <tbody>
            <tr class="hover:bg-gray-50 border print:border-2">
              <td class="px-4 py-2 border text-center">{{ dyeing.unit }}</td>
              <td class="px-4 py-2 border text-center">{{ dyeing.available_unit }}</td>
            </tr>
          </tbody>
        </table>

        <!-- Summary Information -->
        <div class="mt-4 space-y-1 text-sm">
          <p class="font-bold">Total Due: {{ props.selectedParty.due_amount }}</p>
          <p class="font-bold">
            Last Paid Amount: {{ props.dyeingPayment.amount || 0 }}
          </p>
          <p class="font-bold">
            Last Paid Date: {{ props.dyeingPayment.created_at || "-" }}
          </p>
        </div>
      </div>

      <!-- Print Reminder -->
      <div class="mt-6 text-center text-xs text-gray-500 print:hidden">
        Press the Print button or Ctrl+P to print this summary.
      </div>
    </div>
  </div>
</template>


<style scoped>
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
