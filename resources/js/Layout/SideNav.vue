<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import Yarn from "./Yarn.vue";
import Knitting from "./Knitting.vue";
import Sewing from "./Sewing.vue";
import Fabric from "./Fabric.vue";
import Dyeing from "./Dyeing.vue";
import Cutting from "./Cutting.vue";

const page = usePage();
const currentUrl = computed(() => page.url);
const isActiveRoute = (route) => currentUrl.value.startsWith(route);
const sidebarOpen = ref(false);
const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};
</script>

<template>
    <div class="flex min-h-screen bg-gray-100 font-sans">
        <!-- Mobile Toggle Button -->
        <button
            @click="toggleSidebar"
            class="md:hidden fixed top-4 left-4 z-30 p-2 bg-white rounded-md shadow"
        >
            <span class="material-icons text-gray-700">menu</span>
        </button>

        <!-- Sidebar -->
        <aside
            :class="[
                'w-64 bg-gray-800 text-white fixed inset-y-0 left-0 transform transition-transform duration-300 z-20 shadow-xl',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                'md:translate-x-0',
            ]"
            class="flex flex-col"
        >
            <div class="p-6 border-b border-gray-700">
                <h1 class="text-2xl font-bold text-white">My Dashboard</h1>
            </div>

            <nav class="p-4 flex-1 overflow-y-auto">
                <ul class="space-y-2">
                    <!-- Users -->
                    <li v-if="page.props.user.can['list-user']">
                        <Link
                            href="/list-user"
                            :class="[
                                `flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200`,
                                isActiveRoute('/list-user')
                                    ? 'bg-gray-700 text-white'
                                    : 'hover:bg-gray-700',
                            ]"
                        >
                            <span class="material-icons">groups</span>
                            <span>Users</span>
                        </Link>
                    </li>

                    <!-- Roles -->
                    <li v-if="page.props.user.can['list-role']">
                        <Link
                            href="/list-role"
                            :class="[
                                `flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200`,
                                isActiveRoute('/list-role')
                                    ? 'bg-gray-700 text-white'
                                    : 'hover:bg-gray-700',
                            ]"
                        >
                            <span class="material-icons"
                                >admin_panel_settings</span
                            >
                            <span>Role</span>
                        </Link>
                    </li>

                    <!-- Yarn Dropdown -->
                    <Yarn />

                    <!-- Knitting Dropdown -->
                    <Knitting />

                    <!-- Dyeing Dropdown -->
                    <Dyeing />

                    <!-- Cutting Dropdown -->
                    <Cutting />

                    <!-- Sweing Dropdown -->
                    <Sewing />

                    <!-- customer -->
                    <li v-if="page.props.user.can['customer-list']">
                        <Link
                            href="/customer-list"
                            :class="[
                                'flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200',
                                isActiveRoute('/customer-list')
                                    ? 'bg-gray-700 text-blue-300 font-semibold'
                                    : '',
                            ]"
                        >
                            <span class="material-icons"> person </span>
                            <span>Customer</span>
                        </Link>
                    </li>

                    <!-- Category -->
                    <li v-if="page.props.user.can['list-category']">
                        <Link
                            href="/list-category"
                            :class="[
                                'flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200',
                                isActiveRoute('/list-category')
                                    ? 'bg-gray-700 text-blue-300 font-semibold'
                                    : '',
                            ]"
                        >
                            <span class="material-icons">category</span>
                            <span>Category</span>
                        </Link>
                    </li>

                    <!-- Fabric Dropdown -->
                    <Fabric />
                    <!-- Product -->
                    <li v-if="page.props.user.can['product-list']">
                        <Link
                            href="/product-list"
                            :class="[
                                'flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200',
                                isActiveRoute('/list-product')
                                    ? 'bg-gray-700 text-blue-300 font-semibold'
                                    : '',
                            ]"
                        >
                            <span class="material-icons">inventory_2</span>
                            <span>Product</span>
                        </Link>
                    </li>

                    <!-- Sale -->

                    <li v-if="page.props.user.can['sale-page']">
                        <Link
                            href="/sale-page"
                            :class="[
                                'flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200',
                                isActiveRoute('/sale-page')
                                    ? 'bg-gray-700 text-blue-300 font-semibold'
                                    : '',
                            ]"
                        >
                            <span class="material-icons">shopping_cart</span>
                            <span>Sale</span>
                        </Link>
                    </li>

                    <!-- Sale -->

                    <li v-if="page.props.user.can['invoice-list']">
                        <Link
                            href="/invoice-list"
                            :class="[
                                'flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200',
                                isActiveRoute('/invoice-list')
                                    ? 'bg-gray-700 text-blue-300 font-semibold'
                                    : '',
                            ]"
                        >
                            <span class="material-icons">receipt</span>
                            <span>Invoice</span>
                        </Link>
                    </li>
                </ul>
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t">
                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    class="w-full px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600"
                >
                    Logout
                </Link>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 ml-0 md:ml-64 bg-white">
            <slot></slot>
        </main>
    </div>
</template>

<style scoped>
.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 0.3s ease;
}
.slide-fade-enter-from {
    opacity: 0;
    transform: translateY(-10px);
}
.slide-fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
