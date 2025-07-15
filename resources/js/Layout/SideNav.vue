<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();
const currentUrl = computed(() => page.url);

const yarnDropdown = ref(page.url.includes("yarn"));
const knittingDropdown = ref(page.url.includes("knitting"));
const dyeingDropdown = ref(page.url.includes("dyeing"));
const cuttingDropdown = ref(page.url.includes("cutting"));
const sweingDropdown = ref(page.url.includes("sewing"));
const fabricDropdown = ref(page.url.includes("fabric"));

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
                    <li>
                        <Link
                            href="/list-user"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200"
                        >
                            <span class="material-icons">groups</span>
                            <span>Users</span>
                        </Link>
                    </li>

                    <!-- Yarn Dropdown -->
                    <li>
                        <div
                            @click="yarnDropdown = !yarnDropdown"
                            :class="[
                                'flex items-center gap-3 px-4 py-3 cursor-pointer rounded-lg transition-all duration-300',
                                yarnDropdown
                                    ? 'bg-gray-700 text-white'
                                    : 'hover:bg-gray-700',
                            ]"
                        >
                            <span class="material-icons">storefront</span>
                            <span>Yarn</span>
                            <span
                                class="ml-auto transform transition-transform duration-300"
                                :class="{ 'rotate-180': yarnDropdown }"
                            >
                                <span class="material-icons">expand_more</span>
                            </span>
                        </div>
                        <transition name="slide-fade">
                            <ul v-if="yarnDropdown" class="ml-6 mt-2 space-y-2">
                                <li>
                                    <Link
                                        href="/yarn-party-list"
                                        :class="[
                                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                                            isActiveRoute('/yarn-party-list')
                                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                                : '',
                                        ]"
                                    >
                                        <span class="material-icons"
                                            >groups</span
                                        >
                                        Yarn Party List
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/yarn-purchase-list"
                                        :class="[
                                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                                            isActiveRoute('/yarn-purchase-list')
                                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                                : '',
                                        ]"
                                    >
                                        <span class="material-icons"
                                            >shopping_cart</span
                                        >
                                        Yarn Purchase
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/yarn-sale-list"
                                        :class="[
                                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                                            isActiveRoute('/yarn-sale-list')
                                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                                : '',
                                        ]"
                                    >
                                        <span class="material-icons">sell</span>
                                        Yarn Sale List
                                    </Link>
                                </li>
                            </ul>
                        </transition>
                    </li>

                    <!-- Knitting Dropdown -->
                    <li>
                        <div
                            @click="knittingDropdown = !knittingDropdown"
                            :class="[
                                'flex items-center gap-3 px-4 py-3 cursor-pointer rounded-lg transition-all duration-300',
                                knittingDropdown
                                    ? 'bg-gray-700 text-white'
                                    : 'hover:bg-gray-700',
                            ]"
                        >
                            <span class="material-icons">design_services</span>
                            <span>Knitting</span>
                            <span
                                class="ml-auto transform transition-transform duration-300"
                                :class="{ 'rotate-180': knittingDropdown }"
                            >
                                <span class="material-icons">expand_more</span>
                            </span>
                        </div>
                        <transition name="slide-fade">
                            <ul
                                v-if="knittingDropdown"
                                class="ml-6 mt-2 space-y-2"
                            >
                                <li>
                                    <Link
                                        href="/knitting-party-list"
                                        :class="[
                                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                                            isActiveRoute(
                                                '/knitting-party-list'
                                            )
                                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                                : '',
                                        ]"
                                    >
                                        <span class="material-icons"
                                            >groups</span
                                        >
                                        Knitting Party List
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/knitting-list"
                                        :class="[
                                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                                            isActiveRoute('/knitting-list')
                                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                                : '',
                                        ]"
                                    >
                                        <span class="material-icons"
                                            >reorder</span
                                        >
                                        Knitting
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/knitting-receive-list"
                                        :class="[
                                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                                            isActiveRoute(
                                                '/knitting-receive-list'
                                            )
                                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                                : '',
                                        ]"
                                    >
                                        <span class="material-icons"
                                            >inventory_2</span
                                        >
                                        Knitting Receive
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/knitting-sale-list"
                                        :class="[
                                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                                            isActiveRoute('/knitting-sale-list')
                                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                                : '',
                                        ]"
                                    >
                                        <span class="material-icons">sell</span>
                                        Knitting Sale List
                                    </Link>
                                </li>
                            </ul>
                        </transition>
                    </li>

                    <!-- Dyeing Dropdown -->
                    <li>
                        <div
                            @click="dyeingDropdown = !dyeingDropdown"
                            :class="[
                                'flex items-center gap-3 px-4 py-3 cursor-pointer rounded-lg transition-all duration-300',
                                dyeingDropdown
                                    ? 'bg-gray-700 text-white'
                                    : 'hover:bg-gray-700',
                            ]"
                        >
                            <span class="material-icons">color_lens</span>
                            <span>Dyeing</span>
                            <span
                                class="ml-auto transform transition-transform duration-300"
                                :class="{ 'rotate-180': dyeingDropdown }"
                            >
                                <span class="material-icons">expand_more</span>
                            </span>
                        </div>
                        <transition name="slide-fade">
                            <ul
                                v-if="dyeingDropdown"
                                class="ml-6 mt-2 space-y-2"
                            >
                                <li>
                                    <Link
                                        href="/dyeing-party-list"
                                        :class="[
                                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                                            isActiveRoute('/dyeing-party-list')
                                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                                : '',
                                        ]"
                                    >
                                        <span class="material-icons"
                                            >groups</span
                                        >
                                        Dyeing Party List
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/dyeing-list"
                                        :class="[
                                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                                            isActiveRoute('/dyeing-list')
                                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                                : '',
                                        ]"
                                    >
                                        <span class="material-icons"
                                            >palette</span
                                        >
                                        Dyeing
                                    </Link>
                                </li>
                            </ul>
                        </transition>
                    </li>

                    <!-- Cutting Dropdown -->
                    <li>
                        <div
                            @click="cuttingDropdown = !cuttingDropdown"
                            :class="[
                                'flex items-center gap-3 px-4 py-3 cursor-pointer rounded-lg transition-all duration-300',
                                cuttingDropdown
                                    ? 'bg-gray-700 text-white'
                                    : 'hover:bg-gray-700',
                            ]"
                        >
                            <span class="material-icons">content_cut</span>
                            <span>Cutting</span>
                            <span
                                class="ml-auto transform transition-transform duration-300"
                                :class="{ 'rotate-180': cuttingDropdown }"
                            >
                                <span class="material-icons">expand_more</span>
                            </span>
                        </div>
                        <transition name="slide-fade">
                            <ul
                                v-if="cuttingDropdown"
                                class="ml-6 mt-2 space-y-2"
                            >
                                <li>
                                    <Link
                                        href="/cutting-party-list"
                                        :class="[
                                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                                            isActiveRoute('/cutting-party-list')
                                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                                : '',
                                        ]"
                                    >
                                        <span class="material-icons">list</span>
                                        Cutting Party List
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/cutting-list"
                                        :class="[
                                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                                            isActiveRoute('/cutting-list')
                                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                                : '',
                                        ]"
                                    >
                                        <span class="material-icons">list</span>
                                        Cutting List
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/cutting-receive-list"
                                        :class="[
                                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                                            isActiveRoute(
                                                '/cutting-receive-list'
                                            )
                                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                                : '',
                                        ]"
                                    >
                                        <span class="material-icons">list</span>
                                        Cutting Receive
                                    </Link>
                                </li>
                            </ul>
                        </transition>
                    </li>

                    <!-- Sweing Dropdown -->
                    <li>
                        <div
                            @click="sweingDropdown = !sweingDropdown"
                            :class="[
                                'flex items-center gap-3 px-4 py-3 cursor-pointer rounded-lg transition-all duration-300',
                                sweingDropdown
                                    ? 'bg-gray-700 text-white'
                                    : 'hover:bg-gray-700',
                            ]"
                        >
                            <span class="material-icons">check</span>
                            <span>Sweing</span>
                            <span
                                class="ml-auto transform transition-transform duration-300"
                                :class="{ 'rotate-180': sweingDropdown }"
                            >
                                <span class="material-icons">expand_more</span>
                            </span>
                        </div>
                        <transition name="slide-fade">
                            <ul
                                v-if="sweingDropdown"
                                class="ml-6 mt-2 space-y-2"
                            >
                                <li>
                                    <Link
                                        href="/sewing-party-list"
                                        :class="[
                                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                                            isActiveRoute('/sewing-party-list')
                                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                                : '',
                                        ]"
                                    >
                                        <span class="material-icons">list</span>
                                        Sweing Party List
                                    </Link>
                                </li>

                                <li>
                                    <Link
                                        href="/sewing-list"
                                        :class="[
                                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                                            isActiveRoute('/sewing-list')
                                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                                : '',
                                        ]"
                                    >
                                        <span class="material-icons">list</span>
                                        Sweing List
                                    </Link>
                                </li>
                            </ul>
                        </transition>
                    </li>

                    <!-- customer -->
                    <li>
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
                    <li>
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
                    <li>
                        <div
                            @click="fabricDropdown = !fabricDropdown"
                            :class="[
                                'flex items-center gap-3 px-4 py-3 cursor-pointer rounded-lg transition-all duration-300',
                                fabricDropdown
                                    ? 'bg-gray-700 text-white'
                                    : 'hover:bg-gray-700',
                            ]"
                        >
                            <span class="material-icons">checkroom</span>
                            <span>Fabric</span>
                            <span
                                class="ml-auto transform transition-transform duration-300"
                                :class="{ 'rotate-180': fabricDropdown }"
                            >
                                <span class="material-icons">expand_more</span>
                            </span>
                        </div>
                        <transition name="slide-fade">
                            <ul
                                v-if="fabricDropdown"
                                class="ml-6 mt-2 space-y-2"
                            >
                                <li>
                                    <Link
                                        href="/fabric-list"
                                        :class="[
                                            isActiveRoute('/fabric-list')
                                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                                : '',
                                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                                        ]"
                                    >
                                        <span class="material-icons"
                                            >checkroom</span
                                        >
                                        Fabric List
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/fabric-sale-list"
                                        :class="[
                                            isActiveRoute('/fabric-sale-list')
                                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                                : '',
                                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                                        ]"
                                    >
                                        <span class="material-icons">sell</span>
                                        Fabric Sale
                                    </Link>
                                </li>
                            </ul>
                        </transition>
                    </li>

                    <!-- Product -->
                    <li>
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

                    <li>
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

                    <li>
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
