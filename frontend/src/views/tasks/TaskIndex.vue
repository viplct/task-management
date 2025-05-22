<template>
  <DashboardLayout>
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Tasks for Project #{{ projectId }}</h1>
      <button @click="openForm()" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Task</button>
    </div>

    <div class="flex flex-wrap gap-4 items-center mb-4">
      <select v-model="filter.status" class="border p-2 rounded">
        <option value="">All Status</option>
        <option value="todo">To Do</option>
        <option value="in_progress">In Progress</option>
        <option value="done">Done</option>
      </select>

      <select v-model="filter.priority" class="border p-2 rounded">
        <option value="">All Priority</option>
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
      </select>
    </div>

    <p v-if="filter.status || filter.priority" class="text-sm text-red-500">
      Drag & drop is disabled while filters are active.
    </p>

    <Draggable
        :disabled="isFiltered"
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

    <p v-if="tasks.length === 0" class="text-gray-500 text-sm text-center mt-4">
      No tasks found.
    </p>

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
import { ref, onMounted, watch, computed } from 'vue'
import { useRoute } from 'vue-router'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import api from '@/plugins/axios'
import { useToast } from 'vue-toastification'
import { VueDraggableNext as Draggable } from 'vue-draggable-next'
import TaskFormModal from '@/components/modals/TaskFormModal.vue'

const formVisible = ref(false)
const selectedTask = ref(null)

const isFiltered = computed(() =>
    filter.value.status !== '' || filter.value.priority !== ''
)
const openForm = (task = null) => {
  selectedTask.value = task
  formVisible.value = true
}

const toast = useToast()
const route = useRoute()
const projectId = route.params.projectId
const tasks = ref([])
const allTasks = ref([])
const filter = ref({ status: '', priority: '' })

const fetchTasks = async () => {
  try {
    const { data } = await api.get(`/projects/${projectId}/tasks`)
    allTasks.value = data.data || []
    applyFilterAndSort()
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

    // update allTasks to reflect new order
    for (const item of payload) {
      const target = allTasks.value.find(t => t.id === item.id)
      if (target) target.order = item.order
    }
  } catch (e) {
    toast.error('Failed to reorder tasks')
  }
}

const deleteTask = async (taskId) => {
  if (!confirm('Are you sure you want to delete this task?')) return

  try {
    await api.delete(`/tasks/${taskId}`)
    toast.success('Task deleted')

    // Remove from allTasks manually
    allTasks.value = allTasks.value.filter(t => t.id !== taskId)

    // Recalculate order and sync backend
    const payload = allTasks.value.map((task, index) => ({
      id: task.id,
      order: index + 1
    }))
    await api.post(`/projects/${projectId}/tasks/reorder`, { tasks: payload })

    // Apply filtered + sorted view again
    applyFilterAndSort()
  } catch (e) {
    toast.error('Failed to delete task')
  }
}

const applyFilterAndSort = () => {
  let filtered = [...allTasks.value]

  if (filter.value.status)
    filtered = filtered.filter(t => t.status === filter.value.status)

  if (filter.value.priority)
    filtered = filtered.filter(t => t.priority === filter.value.priority)

  tasks.value = filtered
}

// re-apply when filters or sort change
watch([filter], applyFilterAndSort, { deep: true })

onMounted(fetchTasks)
</script>
`
