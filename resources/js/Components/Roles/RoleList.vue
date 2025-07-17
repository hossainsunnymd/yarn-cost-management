<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster();
const page = usePage();

function deleteRole(id){
    if(confirm("Are you sure you want to delete this role?")){
        router.get(`delete-role?role_id=${id}`);
    }
}

if(page.props.flash.status === true){
    toaster.success(page.props.flash.message);
} else if(page.props.flash.status === false){
    toaster.error(page.props.flash.message);
}
</script>

<template>
  <div class="p-6 bg-gray-50 min-h-screen">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
      <h2 class="text-3xl font-extrabold text-gray-800 mb-4 sm:mb-0">Role & Permissions</h2>
      <div v-if="page.props.user.can['create-role']">
        <Link
          :href="`/role-save-page?role_id=0`"
          class="inline-block bg-green-600 hover:bg-green-700 transition-colors text-white font-semibold py-2 px-5 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2transition duration-300"
        >
          Create
        </Link>
      </div>
    </div>

    <div class="overflow-x-auto rounded-lg shadow-lg bg-white">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">Role ID</th>
            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">Role Name</th>
            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">Permissions</th>
            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="role in page.props.roles" :key="role.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ role.id }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ role.name }}</td>
            <td class="px-6 py-4 whitespace-normal text-sm">
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="perm in role.permissions"
                  :key="perm.id"
                  class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full "
                >
                  {{ perm.name }}
                </span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
              <Link
                v-if="page.props.user.can['update-role']"
                :href="`/role-save-page?role_id=${role.id}`"
                class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2transition duration-300"
              >
                Edit
              </Link>
              <button
                v-if="page.props.user.can['delete-role']"
                @click="deleteRole(role.id)"
                class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md shadow-sm  focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-300"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
