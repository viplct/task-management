<template>
  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div
        :class="[
        'fixed z-40 inset-y-0 left-0 w-64 bg-gray-800 text-white transform transition-transform duration-200',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        'lg:relative lg:translate-x-0 lg:w-64'
      ]"
    >
      <div class="p-6 space-y-4">
        <h1 class="text-xl font-bold">Task Manager</h1>
        <nav class="space-y-2">
          <router-link to="/dashboard" class="block hover:text-gray-300">Dashboard</router-link>
          <router-link to="/projects" class="block hover:text-gray-300">Projects</router-link>
          <button @click="logout" class="mt-4 text-sm text-red-400 hover:text-red-300">Logout</button>
        </nav>
      </div>
    </div>

    <!-- Main content wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Top navbar -->
      <header class="bg-white shadow px-4 py-3 flex items-center justify-between lg:hidden">
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 text-2xl">
          &#9776;
        </button>
        <span class="font-semibold">Task Manager</span>
      </header>

      <!-- Main content -->
      <main class="flex-1 overflow-y-auto bg-gray-100 p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuth } from '@/composables/useAuth'

const { logout } = useAuth()
const sidebarOpen = ref(false)
</script>
