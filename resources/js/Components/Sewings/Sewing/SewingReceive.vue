<script setup>
import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router } from "@inertiajs/vue3";
import { computed } from "vue";
import ImageUpload from "../../ImageUpload.vue";

const errors = computed(() => page.props.flash.error || {});
const toaster = createToaster({});
const page = usePage();
const sewingId = new URLSearchParams(window.location.search).get("sewing_id");

const form = useForm({
    sewing_id: sewingId,
    unit: "",
    wastage: "",
    sewing_cost: "",
    image: "",
});
let URL = "/create-sewing-receive";

function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status == false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status == true) {
                toaster.success(page.props.flash.message);
                router.visit("/sewing-list");
            }
        },
    });
}
</script>

<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            Receive Product
        </h2>

        <form @submit.prevent="submitForm" class="space-y-5">
            <div>
                <label
                    for="unit"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Unit</label
                >
                <input
                    v-model="form.unit"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.unit" class="text-red-500 text-md mt-1">
                    {{ errors.unit[0] }}
                </p>
            </div>

            <div>
                <label
                    for="t"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Sewing Cost</label
                >
                <input
                    v-model="form.sewing_cost"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.sewing_cost" class="text-red-500 text-md mt-1">
                    {{ errors.sewing_cost[0] }}
                </p>
            </div>
            <div>
                <label
                    for="t"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Wastage</label
                >
                <input
                    v-model="form.wastage"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>

            <!-- image upload -->
            <div>
                <label for="image">Product Image:</label> <br />
                <ImageUpload
                    :productImage="form.image"
                    @image="(e) => (form.image = e)"
                />
                <p v-if="errors.image" class="text-red-500">
                    {{ errors.image[0] }}
                </p>
            </div>

            <div class="pt-3">
                <button
                    type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition duration-300"
                >
                    Confirm
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped></style>
