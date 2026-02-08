<script setup>
const props = defineProps({
    products: Object,
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

            <!-- Print button -->
            <button
                @click="printModal"
                class="absolute top-3 left-3 text-sm bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition print:hidden"
                aria-label="Print modal content"
            >
                🖨️ Print
            </button>

            <!-- Company header (centered) -->
            <div class="w-full text-center mt-2 print:mt-0">
                <h1 class="text-2xl font-bold">
                    মেসার্স শফি নিটিং এন্ড হোসিয়ারী
                </h1>
                <div class="text-sm text-gray-600">
                    উন্নতমানের সুতী গেঞ্জী ও জাইঙ্গা প্রস্তুতকারক ও পাইকারী
                    বিক্রেতা।
                </div>
                <div class="text-sm text-gray-600">
                    ৫৫/১, নয়ামাটি, কলিমুল্লাহ মার্কেট ,নারায়ণগঞ্জ ।
                </div>
            </div>

            <div class="w-full mt-2 print:mt-0">
                <h1 class="font-bold">
                    চালান নং-{{
                        props.products && props.products.challan_no
                            ? props.products.challan_no
                            : ""
                    }}
                </h1>
                <h1 class="font-bold">
                    নাম:
                    {{
                        props.products && props.products.customer
                            ? props.products.customer.name
                            : ""
                    }}
                </h1>
                <h1 class="font-bold">
                    ঠিকানা:
                    {{
                        props.products && props.products.customer
                            ? props.products.customer.address
                            : ""
                    }}
                </h1>
                <h1 class="font-bold">
                    মোবাইল:
                    {{
                        props.products && props.products.customer
                            ? props.products.customer.phone
                            : ""
                    }}
                </h1>
                <h1 class="font-bold">
                    তারিখ:
                    {{
                        props.products && props.products.invoice_date
                            ? new Date(
                                  props.products.invoice_date,
                              ).toLocaleDateString('en-GB')
                            : ""
                    }}
                </h1>
            </div>

            <!-- Products Table -->
            <div class="overflow-x-auto overflow-y-auto mt-6">
                <table class="w-full border border-gray-300 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th
                                class="px-4 py-2 border text-center"
                                style="max-width: 200px"
                            >
                                বিবরন
                            </th>
                            <th class="px-4 py-2 border text-center">ডজন</th>
                            <th class="px-4 py-2 border text-center">দর</th>
                            <th class="px-4 py-2 border text-center">টাকা</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(item, index) in props.products
                                .invoice_products"
                            :key="index"
                            class="hover:bg-gray-50 border-1 print:border-2"
                        >
                            <td class="px-4 py-2 border text-center">
                                {{
                                    item.sewing_receive.sewing.cutting_receive
                                        .cutting.category.name
                                }}
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
                    <tfoot>
                        <tr class="bg-gray-100 font-semibold">
                            <td
                                class="px-4 py-2 border text-center"
                                colspan="3"
                            >
                                মোট
                            </td>
                            <td class="px-4 py-2 border text-center">
                                {{
                                    props.products &&
                                    props.products.total_amount
                                        ? props.products.total_amount
                                        : ""
                                }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-6 text-center text-xs">
                উপরুক্ত মাল ভাল অবস্থায় বুঝিয়া পাইলাম
            </div>

            <div class="mt-6 text-center text-xs flex justify-between px-20">
                <h1>ক্রেতার স্বাক্ষর</h1>
                <h1>বিক্রেতার স্বাক্ষর</h1>
            </div>

            <!-- Footer note shown only on screen -->
            <div class="mt-6 text-center text-xs text-gray-500 print:hidden">
                Press the Print button or Ctrl+P to print this summary.
            </div>
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
