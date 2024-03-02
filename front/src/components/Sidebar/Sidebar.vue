<script setup>
import SidebarLink from './SidebarLink.vue';
import { ref, computed, provide } from 'vue';
import { RouteGenerator } from '../../router/RouteGenerator';

const collapsed = ref(false);
const toggleSidebar = () => (collapsed.value = !collapsed.value);

const SIDEBAR_WIDTH = 180;
const SIDEBAR_WIDTH_COLLAPSED = 45;
const sidebarWidth = computed(
  () => `${collapsed.value ? SIDEBAR_WIDTH_COLLAPSED : SIDEBAR_WIDTH}px`
);

provide('collapsed', collapsed);
</script>

<template>
    <nav class="p-3 bg-zinc-800 text-white h-dvh transition-all duration-300 flex flex-col justify-between" :style="{ width: sidebarWidth }">
        <div>
            <h1 class="text-center">
                <span class="*:block" v-if="collapsed">
                    <span>T</span>
                    <span>M</span>
                </span>
                <span v-else>Tandemite</span>
            </h1>
            <hr class="rounded my-2">

            <div class="*:py-1.5 gap-2 flex flex-col">
                <SidebarLink :to="RouteGenerator.generateRoute('addClient')" icon="fa-solid fa-user-plus" name="Add client"/>
                <SidebarLink :to="RouteGenerator.generateRoute('listClients')" icon="fa-solid fa-users" name="List of clients"/>
            </div>
        </div>

        <button
            type="button"
            class="flex justify-center items-center px-3 border-2 rounded-full border-transparent transition duration-150 hover:border-white"
            @click="toggleSidebar"
        >
            <font-awesome-icon icon="fa-solid fa-angles-left" :class="{ 'rotate-180': collapsed }"/>
        </button>
    </nav>
</template>
