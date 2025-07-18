<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();
const currentUrl = computed(() => page.url);
const isActiveRoute = (route) => currentUrl.value.startsWith(route);
const cuttingDropdown = ref(page.url.includes("cutting"));
</script>

<template>
    <li v-if="page.props.user.can['cutting-dropdown']">
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
            <ul v-if="cuttingDropdown" class="ml-6 mt-2 space-y-2">
                <li v-if="page.props.user.can['cutting-party-list']">
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
                <li v-if="page.props.user.can['cutting-list']">
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
                <li v-if="page.props.user.can['cutting-receive-list']">
                    <Link
                        href="/cutting-receive-list"
                        :class="[
                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                            isActiveRoute('/cutting-receive-list')
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
