<script setup>
import { computed } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router, Link } from "@inertiajs/vue3";
import ImageUpload from "../ImageUpload.vue";

const toaster = createToaster({});
const page = usePage();

const product = page.props.product;
const errors = computed(() => page.props.flash.error || {});

let URL = "/update-product";

const form = useForm({
    sewing_receive_id: product.id,
    image: product.image,
});

function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status == false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status == true) {
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
            <div class="float-end">
                <Link
                    href="/list-product"
                    class="inline-block bg-green-600 hover:bg-green-700 text-white py-1 px-3 text-sm rounded mx-3 transition duration-300"
                >
                    Back
                </Link>
            </div>

            <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                Update Product
            </h2>

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

            <!-- Submit Button -->
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
