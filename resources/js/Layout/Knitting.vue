<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();
const currentUrl = computed(() => page.url);
const isActiveRoute = (route) => {
    return page.url === route;
};

const knittingDropdown = ref(page.url.includes("knitting"));
</script>

<template>
    <li v-if="page.props.user.can['knitting-dropdown']">
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
            <ul v-if="knittingDropdown" class="ml-6 mt-2 space-y-2">
                <li v-if="page.props.user.can['knitting-party-list']">
                    <Link
                        href="/knitting-party-list"
                        :class="[
                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                            isActiveRoute('/knitting-party-list')
                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                : '',
                        ]"
                    >
                        <span class="material-icons">groups</span>
                        Knitting Party List
                    </Link>
                </li>
                <li v-if="page.props.user.can['knitting-list']">
                    <Link
                        href="/knitting-list"
                        :class="[
                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                            isActiveRoute('/knitting-list')
                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                : '',
                        ]"
                    >
                        <span class="material-icons">reorder</span>
                        Knitting
                    </Link>
                </li>
                <li v-if="page.props.user.can['knitting-receive-list']">
                    <Link
                        href="/knitting-receive-list"
                        :class="[
                            'flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-700',
                            isActiveRoute('/knitting-receive-list')
                                ? 'bg-gray-700 text-blue-300 font-semibold'
                                : '',
                        ]"
                    >
                        <span class="material-icons">inventory_2</span>
                        Knitting Receive
                    </Link>
                </li>
                <li v-if="page.props.user.can['knitting-sale-list']">
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
