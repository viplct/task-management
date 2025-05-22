<template>
  <DashboardLayout>
    <h2 class="text-2xl font-bold mb-4">Dashboard</h2>
    <div class="bg-white p-6 rounded shadow">
      <h3 class="text-lg font-semibold">Total Projects</h3>
      <p class="text-3xl">{{ projectCount }}</p>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import api from '@/plugins/axios'

const projectCount = ref(0)

onMounted(async () => {
  const { data } = await api.get('/projects')
  projectCount.value = data?.data?.length ?? 0
})
</script>