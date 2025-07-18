<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();
const yarnDropdown = ref(page.url.includes("yarn"));
const isActiveRoute = (route) => currentUrl.value.startsWith(route);
const currentUrl = computed(() => page.url);
</script>

<template>
    <li v-if="page.props.user.can['yarn-dropdown']">
        <div
            @click="yarnDropdown = !yarnDropdown"
            :class="[
                'flex items-center gap-3 px-4 py-3 cursor-pointer rounded-lg transition-all duration-300',
                yarnDropdown ? 'bg-gray-700 text-white' : 'hover:bg-gray-700',
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
                <li v-if="page.props.user.can['yarn-party-list']">
                    <Link
                        href="/yarn-party-list"
                        :class="[
                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                            isActiveRoute('/yarn-party-list')
                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                : '',
                        ]"
                    >
                        <span class="material-icons">groups</span>
                        Yarn Party List
                    </Link>
                </li>
                <li v-if="page.props.user.can['yarn-purchase-list']">
                    <Link
                        href="/yarn-purchase-list"
                        :class="[
                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                            isActiveRoute('/yarn-purchase-list')
                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                : '',
                        ]"
                    >
                        <span class="material-icons">shopping_cart</span>
                        Yarn Purchase
                    </Link>
                </li>
                <li v-if="page.props.user.can['yarn-sale-list']">
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
