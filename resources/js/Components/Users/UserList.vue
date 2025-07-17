<script setup>
import { ref } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster({});
const page = usePage();
const headers = [
    { text: "ID", value: "id" },
    { text: "Name", value: "name" },
    { text: "Email", value: "email" },
    { text: "Role", value: "role" },
    { text: "Phone", value: "phone" },
    { text: "Action", value: "action" },
];
const items = ref(page.props.users);
const searchField = ref(["id", "name", "email"]);
const searchItem = ref();

function deleteUser(id) {
    if (confirm("Are you sure you want to delete this user?")) {
        router.get(`/delete-user?user_id=${id}`);
    }
}

if (page.props.flash.status == false) {
    toaster.error(page.props.flash.message);
} else if (page.props.flash.status == true) {
    toaster.success(page.props.flash.message);
}
</script>

<template>
    <p class="text-2xl font-bold">User List</p>
    <div class="flex justify-between">
        <div>
            <input
                type="text"
                class="border border-gray-300 rounded-md px-4 py-2 w-[300px]"
                v-model="searchItem"
                placeholder="Search here"
            />
        </div>
        <div v-if="page.props.user.can['create-user']">
            <Link
                :href="`/user-save-page?user_id=${0}`"
                class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition duration-300"
                >Add User</Link
            >
        </div>
    </div>
    <EasyDataTable
        :headers="headers"
        :items="items"
        alternating
        :rows-per-page="5"
        :search-field="searchField"
        :search-value="searchItem"
    >
        <template #item-role="{ roles }">
            <span class="m-1" v-for="role in roles" :key="role.id"
                >{{ role.name }}
            </span>
        </template>
        <template #item-action="{ id }">
            <Link v-if="page.props.user.can['update-user']"
                :href="`/user-save-page?user_id=${id}`"
                class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600 transition duration-300"
                >Edit</Link
            >
            <button v-if="page.props.user.can['delete-user']"
                @click="deleteUser(id)"
                class="bg-red-500 text-white font-bold py-2 px-4 rounded ml-1 cursor-pointer hover:bg-red-600 transition duration-300"
            >
                Delete
            </button>
        </template>
    </EasyDataTable>
</template>

<style scoped></style>
