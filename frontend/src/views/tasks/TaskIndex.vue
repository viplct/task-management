<template>
  <DashboardLayout>
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Tasks for Project #{{ projectId }}</h1>
      <button @click="openForm()" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Task</button>
    </div>

    <Draggable
        :list="tasks"
        item-key="id"
        tag="div"
        class="space-y-3"
        @change="reorderTasks"
    >
      <div
          v-for="element in tasks"
          :key="element.id"
          class="bg-white p-4 rounded shadow flex justify-between items-center"
      >
        <div>
          <h3 class="font-semibold text-lg">{{ element.title }}</h3>
          <p class="text-sm text-gray-500">Due: {{ element.due_date }}</p>
          <p class="text-xs text-gray-500">Priority: {{ element.priority }}</p>
          <p class="text-xs text-gray-500">Status: {{ element.status }}</p>
        </div>
        <div class="flex items-center space-x-2">
          <button
              @click="openForm(element)"
              class="text-blue-600 hover:underline text-sm"
          >
            Edit
          </button>
          <button
              @click="deleteTask(element.id)"
              class="text-red-600 hover:underline text-sm"
          >
            Delete
          </button>
          <span class="text-gray-400 cursor-grab text-xl">☰</span>
        </div>
      </div>
    </Draggable>

    <TaskFormModal
        v-if="formVisible"
        :task="selectedTask"
        :project-id="projectId"
        @close="formVisible = false"
        @saved="fetchTasks"
    />
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import api from '@/plugins/axios'
import { useToast } from 'vue-toastification'
import { VueDraggableNext as Draggable } from 'vue-draggable-next'
import TaskFormModal from '@/components/modals/TaskFormModal.vue'

const formVisible = ref(false)
const selectedTask = ref(null)

const openForm = (task = null) => {
  selectedTask.value = task
  formVisible.value = true
}

const toast = useToast()
const route = useRoute()
const projectId = route.params.projectId
const tasks = ref([])

const fetchTasks = async () => {
  try {
    const { data } = await api.get(`/projects/${projectId}/tasks`)
    tasks.value = data.data || []
  } catch (e) {
    toast.error('Failed to load tasks')
  }
}

const reorderTasks = async () => {
  const payload = tasks.value.map((task, index) => ({
    id: task.id,
    order: index + 1
  }))
  try {
    await api.post(`/projects/${projectId}/tasks/reorder`, { tasks: payload })
    toast.success('Tasks reordered successfully')
  } catch (e) {
    toast.error('Failed to reorder tasks')
  }
}

const deleteTask = async (taskId) => {
  if (!confirm('Are you sure you want to delete this task?')) return

  try {
    await api.delete(`/tasks/${taskId}`)
    toast.success('Task deleted')
    await fetchTasks()
  } catch (e) {
    toast.error('Failed to delete task')
  }
}

onMounted(fetchTasks)
</script>
`
