<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();

const fabricDropdown = ref(page.url.includes("fabric"));
const currentUrl = computed(() => page.url);
const isActiveRoute = (route) => {
    return (page.url === currentUrl.value) === route;
};
</script>

<template>
    <li>
        <div
            @click="fabricDropdown = !fabricDropdown"
            :class="[
                'flex items-center gap-3 px-4 py-3 cursor-pointer rounded-lg transition-all duration-300',
                fabricDropdown ? 'bg-gray-700 text-white' : 'hover:bg-gray-700',
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
            <ul v-if="fabricDropdown" class="ml-6 mt-2 space-y-2">
                <li v-if="page.props.user.can['fabric-list']">
                    <Link
                        href="/fabric-list"
                        :class="[
                            isActiveRoute('/fabric-list')
                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                : '',
                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                        ]"
                    >
                        <span class="material-icons">checkroom</span>
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
