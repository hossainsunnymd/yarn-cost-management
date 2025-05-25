<script setup>
import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router } from "@inertiajs/vue3";

const toaster = createToaster({});
const page = usePage();
const dyeingPartyId = new URLSearchParams(window.location.search).get(
    "dyeing_party_id"
);
const dyeingParty = page.props.dyeingParty;

const form = useForm({
    dyeing_party_id: dyeingPartyId,
    name: "",
    phone: "",
    address: "",
});
let URL = "/create-dyeing-party";
if (dyeingPartyId != 0 && dyeingParty != null) {
    form.name = dyeingParty.name;
    form.phone = dyeingParty.phone;
    form.address = dyeingParty.address;
    URL = "/update-dyeing-party";
}

function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status == false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status == true) {
                toaster.success(page.props.flash.message);
                router.visit("/dyeing-party-list");
            }
        },
    });
}
</script>

<template>
    <div class="p-6 max-w-2xl w-full mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            {{ dyeingPartyId == 0 ? "Add dyeing Party" : "Update dyeing Party" }}
        </h2>

        <form @submit.prevent="submitForm" class="space-y-5">
            <div>
                <label
                    for="name"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Name</label
                >
                <input
                    v-model="form.name"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>

               <div>
                <label
                    for="phone"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Phone</label
                >
                <input
                    v-model="form.phone"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>

               <div>
                <label
                    for="address"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Address</label
                >
                <input
                    v-model="form.address"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>

            <div class="pt-3">
                <button
                    type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition duration-300"
                >
                    {{ dyeingPartyId == 0 ? "Add Dyeing Party" : "Update Dyeing Party" }}
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped></style>
