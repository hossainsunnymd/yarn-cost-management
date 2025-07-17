<script setup>
import { router, usePage,useForm } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";
import { computed } from "vue";

const errors=computed(()=>page.props.flash.errors || {});
const toaster = createToaster({ });
const page = usePage();

const roleId=new URLSearchParams(window.location.search).get('role_id');
const form=useForm({
    role_id:roleId,
    roleName:'',
    permissions:[]
})
let URL='/create-role';
if(roleId != 0 && page.props.role != null){
    form.roleName=page.props.role.name;
    form.permissions = page.props.role.permissions.map(p => p.id);
    URL='/update-role';
}

function submitForm(){
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status == false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status == true) {
                router.get('/list-role');
            }
        },
    });
}


</script>

<template>
  <div class="max-w-md mx-auto bg-white shadow-lg rounded-2xl p-6">
    <div>
      <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Role Name:</label>
      <input
        type="text"
        v-model="form.roleName"
        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
      />
      <p v-if="errors.roleName" class="text-red-500">{{ errors.roleName[0] }}</p>
    </div>

    <h2 class="text-xl font-semibold text-gray-800 mt-4">Select Permissions</h2>
    <div class="grid grid-cols-2 gap-4 mt-4">
      <div
        v-for="permission in page.props.permissions"
        :key="permission.id"
        class="flex items-center space-x-2"
      >
        <input
          type="checkbox"
          :value="permission.id"
          v-model="form.permissions"
          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
        />
        <label :for="'permission_' + permission.id" class="text-sm text-gray-700 capitalize">
          {{ permission.name }}
        </label>
      </div>
    </div>
      <button @click="submitForm" type="button" class="w-full bg-blue-600 text-white py-2 rounded-xl hover:bg-blue-700 transition duration-300 mt-5">Save</button>
  </div>
</template>

<style scoped>

</style>
