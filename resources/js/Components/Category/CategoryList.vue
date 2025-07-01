<script setup>
import { ref } from 'vue'
import {router, usePage,Link} from '@inertiajs/vue3'
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({ });
const page=usePage()

const headers = [
    { text: 'ID', value: 'id' },
    { text: 'Name', value: 'name' },
    { text: 'Action', value: 'action' },
];

const items=ref(page.props.categories);
const searchField = ref("name");
const searchItem=ref();

function deleteCategory(id){
    if(confirm("Are you sure you want to delete this category?")){
        router.visit(`/delete-category?category_id=${id}`);
    }
}

if(page.props.flash.status==true){
    toaster.success(page.props.flash.message);
}else if(page.props.flash.status==false){
    toaster.error(page.props.flash.message);

}


</script>

<template>
    <div class="p-4 bg-[#f8f8f8]">
        <h1 class="flex text-2xl font-bold mb-4">Category List</h1>

        <input type="text" class="border border-gray-300 rounded-md px-4 py-2 w-[300px]" v-model="searchItem" placeholder="Search by name">

        <div class="mt-4 mb-4">
            <Link :href="`/category-save-page?category_id=${0}`" class="bg-green-500  text-white py-2 px-4 rounded hover:bg-green-600 transition duration-300">Add Category</Link>
        </div>

        <EasyDataTable
            :headers="headers"
            :items="items"
            alternating
            :rows-per-page="50"
            :search-field="searchField"
            :search-value="searchItem"
        >
            <template #item-action="{ id }">
                <Link
                    :href="`/category-save-page?category_id=${id}`"
                    class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600 transition duration-300"
                >
                    Edit
                </Link>
                <button

                    @click="deleteCategory(id)"
                    class="bg-red-500 text-white font-bold py-2 px-4 rounded ml-1 hover:bg-red-600 transition duration-300"
                >
                    Delete
                </button>
            </template>
        </EasyDataTable>
    </div>
</template>



<style scoped>

</style>
