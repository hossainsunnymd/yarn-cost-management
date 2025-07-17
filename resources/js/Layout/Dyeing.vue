<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();
const currentUrl = computed(() => page.url);
const isActiveRoute = (route) => currentUrl.value.startsWith(route);
const dyeingDropdown = ref(page.url.includes("dyeing"));
</script>

<template>
    <li>
        <div
            @click="dyeingDropdown = !dyeingDropdown"
            :class="[
                'flex items-center gap-3 px-4 py-3 cursor-pointer rounded-lg transition-all duration-300',
                dyeingDropdown ? 'bg-gray-700 text-white' : 'hover:bg-gray-700',
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
            <ul v-if="dyeingDropdown" class="ml-6 mt-2 space-y-2">
                <li v-if="page.props.user.can['dyeing-party-list']">
                    <Link
                        href="/dyeing-party-list"
                        :class="[
                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                            isActiveRoute('/dyeing-party-list')
                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                : '',
                        ]"
                    >
                        <span class="material-icons">groups</span>
                        Dyeing Party List
                    </Link>
                </li>
                <li v-if="page.props.user.can['dyeing-list']">
                    <Link
                        href="/dyeing-list"
                        :class="[
                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                            isActiveRoute('/dyeing-list')
                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                : '',
                        ]"
                    >
                        <span class="material-icons">palette</span>
                        Dyeing
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
