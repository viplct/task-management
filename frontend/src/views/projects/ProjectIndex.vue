<template>
  <DashboardLayout>
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-2xl font-bold">Projects</h2>
      <button @click="openForm()" class="bg-blue-600 text-white px-4 py-2 rounded">+ New Project</button>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
      <div
          v-for="project in projects"
          :key="project.id"
          class="bg-white shadow p-4 rounded flex flex-col justify-between"
      >
        <div>
          <h3 class="text-lg font-semibold">{{ project.title }}</h3>
          <p class="text-sm text-gray-600">{{ project.description }}</p>

          <!-- ✅ Status tag -->
          <span
              class="text-xs inline-block px-2 py-1 rounded font-medium mt-2"
              :class="{
              'bg-green-100 text-green-800': project.status === 'active',
              'bg-gray-200 text-gray-600': project.status === 'inactive'
            }"
                >
            {{ project.status }}
          </span>

          <!-- ✅ Extra info -->
          <div class="text-xs text-gray-500 mt-2">
            <p>🕒 Created: {{ new Date(project.created_at).toLocaleString() }}</p>
            <p>📌 Tasks: {{ project.tasks_count }}</p>
          </div>
        </div>

        <div class="flex items-center justify-between mt-4">
          <!-- 👈 Left-aligned Add Task -->
          <button
              @click="goToTasks(project)"
              class="text-green-600 hover:underline text-sm"
          >
            View Task
          </button>

          <!-- 👉 Right-aligned Edit/Delete -->
          <div class="flex gap-2">
            <button @click="openForm(project)" class="text-blue-600">Edit</button>
            <button @click="destroy(project.id)" class="text-red-600">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <ProjectFormModal
        v-if="formVisible"
        :project="selectedProject"
        @close="formVisible = false"
        @saved="fetchProjects"
    />
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/plugins/axios'
import { useToast } from 'vue-toastification'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import ProjectFormModal from '@/components/modals/ProjectFormModal.vue'
import { useRouter } from 'vue-router'
const router = useRouter()

const toast = useToast()
const projects = ref([])
const formVisible = ref(false)
const selectedProject = ref(null)

const goToTasks = (project) => {
  router.push(`/projects/${project.id}/tasks`)
}

const fetchProjects = async () => {
  const { data } = await api.get('/projects')
  projects.value = data.data || []
}

const openForm = (project = null) => {
  selectedProject.value = project
  formVisible.value = true
}

const destroy = async (id) => {
  if (confirm('Are you sure you want to delete this project?')) {
    await api.delete(`/projects/${id}`)
    toast.success('Project deleted')
    fetchProjects()
  }
}

onMounted(fetchProjects)
</script>
