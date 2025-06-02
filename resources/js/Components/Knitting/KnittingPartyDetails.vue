<script setup>
const props = defineProps({
    items: Array,
    modal: Boolean,
});


const emit = defineEmits(["update:modal"]);

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


      <div class="flex justify-between mb-4">
          <div class="mt-4">


          </div>
          <div class="font-bold">
              <!-- <img class="h-[90px]" src="../../Assets/img/logo.jpg" alt="Logo" /> -->
          </div>
      </div>

      <!-- Close Button -->
      <button @click="$emit('update:modal', false)" class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-2xl print:hidden">
        &times;
      </button>

      <!-- Print Button -->
      <button @click="printModal" class="absolute top-3 left-3 text-sm bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition print:hidden">
        🖨️ Print
      </button>

      <!-- Title -->
      <h1 class="text-2xl font-bold text-left  pb-2">Party Name: {{ props.items[0].name }} </h1>
      <h1 class="text-sm font-bold text-center pb-2">
        <!-- Report Date: {{ formatDate(props.fromDate) }} - {{ formatDate(props.toDate) }} -->
      </h1>

      <!-- Table -->
      <div class=" overflow-x-auto overflow-y-auto">
        <table v-for="(knitting, i) in props.items[0].knittings" :key="i" class="w-full border border-gray-300 text-sm mt-4">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 border text-center ">Weight</th>
              <th class="px-4 py-2 border text-center ">Avialable Weight</th>
            </tr>
          </thead>
          <tbody>
            <tr   class="hover:bg-gray-50 border-1 print:border-2">
              <td class="px-4 py-2 border text-center">{{ knitting.unit }}</td>
              <td class="px-4 py-2 border text-center">{{ knitting.available_unit }}</td>
            </tr>
          </tbody>

        </table>
         <p class="font-bold mt-[20px]">
           {{ props.items[0].total_amount }}
         </p>
         <p class="font-bold">
           {{ props.items[0].due_amount }}
         </p>
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
