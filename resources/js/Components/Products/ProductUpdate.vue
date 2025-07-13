<script setup>
import { computed } from "vue";
import { usePage, useForm, router, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import ImageUpload from "../ImageUpload.vue";

// Initialize toaster for notification messages
const toaster = createToaster({});


const page = usePage();
const product = page.props.product;

// handle validation errors
const errors = computed(() => page.props.flash.error || {});

// URL to send the update request
const URL = "/update-product";

// Initialize the form with product data
const form = useForm({
    sewing_receive_id: product.id,
    image: product.image,
});

//submit form
function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status === false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status === true) {
                toaster.success(page.props.flash.message);
                router.get("/product-list");
            }
        },
    });
}
</script>

<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <form
            @submit.prevent="submitForm"
            class="w-full max-w-lg mx-auto bg-white p-8 rounded-md shadow-md"
        >
            <!-- Back button -->
            <div class="float-end">
                <Link
                    href="/list-product"
                    class="inline-block bg-green-600 hover:bg-green-700 text-white py-1 px-3 text-sm rounded mx-3 transition duration-300"
                >
                    Back
                </Link>
            </div>

            <!-- Form title -->
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                Update Product
            </h2>

            <!-- Image upload component -->
            <div>
                <label for="image" class="block mb-1 font-medium"
                    >Product Image:</label
                >
                <ImageUpload
                    :productImage="form.image"
                    @image="(e) => (form.image = e)"
                />
                <p v-if="errors.image" class="text-red-500 mt-1">
                    {{ errors.image[0] }}
                </p>
            </div>

            <!-- Submit button -->
            <div class="pt-3">
                <button
                    type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition duration-300"
                >
                    Update Product
                </button>
            </div>
        </form>
    </div>
</template>
