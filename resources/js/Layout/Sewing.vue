<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();
const sweingDropdown = ref(page.url.includes("sewing"));
const currentUrl = computed(() => page.url);
const isActiveRoute = (route) => currentUrl.value === route;

</script>

<template>
    <li v-if="page.props.user.can['sewing-dropdown']">
        <div
            @click="sweingDropdown = !sweingDropdown"
            :class="[
                'flex items-center gap-3 px-4 py-3 cursor-pointer rounded-lg transition-all duration-300',
                sweingDropdown ? 'bg-gray-700 text-white' : 'hover:bg-gray-700',
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
            <ul v-if="sweingDropdown" class="ml-6 mt-2 space-y-2">
                <li v-if="page.props.user.can['sewing-party-list']">
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

                <li v-if="page.props.user.can['sewing-list']">
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
