<script setup>
// Props received from parent
const props = defineProps({
  knittingYarn: Object, 
  modal: Boolean
});

// Emits 'update:modal' event to parent
const emit = defineEmits(["update:modal"]);

// Print modal content
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
  <div v-if="modal" class="fixed inset-0 z-50 flex items-center justify-center">
    <div id="modal-content" class="bg-white w-[1100px] max-w-full rounded-xl shadow-2xl p-6 relative print:w-full print:shadow-none print:p-0 h-[500px] overflow-auto">

      <!-- Close Button -->
      <button @click="$emit('update:modal', false)" class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-2xl print:hidden">
        &times;
      </button>

      <!-- Print Button -->
      <button @click="printModal" class="absolute top-3 left-3 text-sm bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition print:hidden">
        🖨️ Print
      </button>


      <!-- Table -->
      <div class=" overflow-x-auto overflow-y-auto mt-20">
        <table class="w-full border border-gray-300 text-sm">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 border text-center ">#</th>
              <th class="px-4 py-2 border text-center ">Per Unit Cost</th>
              <th class="px-4 py-2 border text-center ">Unit</th>

            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in props.knittingYarn.knitting_yarn" :key="index" class="hover:bg-gray-50 border-1 print:border-2">
              <td class="px-4 py-2 border text-center">{{ index + 1 }}</td>
              <td class="px-4 py-2 border text-center">{{ item.per_unit_cost }}</td>
              <td class="px-4 py-2 border text-center">{{ item.unit }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Footer -->
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
