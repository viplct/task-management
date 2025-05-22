<template>
  <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white p-6 w-full max-w-md rounded shadow">
      <h2 class="text-lg font-bold mb-4">{{ task?.id ? 'Edit Task' : 'New Task' }}</h2>
      <form @submit.prevent="submit">
        <input v-model="form.title" placeholder="Title" class="w-full mb-3 p-2 border rounded" />
        <input v-model="form.due_date" type="date" class="w-full mb-3 p-2 border rounded" />
        <select v-model="form.priority" class="w-full mb-3 p-2 border rounded">
          <option value="low">Low</option>
          <option value="medium">Medium</option>
          <option value="high">High</option>
        </select>
        <select v-model="form.status" class="w-full mb-3 p-2 border rounded">
          <option value="todo">To Do</option>
          <option value="in_progress">In Progress</option>
          <option value="done">Done</option>
        </select>
        <div class="flex justify-end gap-2">
          <button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue'
import api from '@/plugins/axios'
import { useToast } from 'vue-toastification'

const props = defineProps({ task: Object, projectId: [Number, String] })
const emit = defineEmits(['close', 'saved'])

const toast = useToast()

const form = reactive({
  title: '',
  due_date: '',
  priority: 'medium',
  status: 'todo'
})

watch(() => props.task, (task) => {
  if (task) Object.assign(form, task)
  else Object.assign(form, { title: '', due_date: '', priority: 'medium', status: 'todo' })
}, { immediate: true })

const submit = async () => {
  try {
    if (props.task?.id) {
      await api.put(`/tasks/${props.task.id}`, form)
      toast.success('Task updated')
    } else {
      await api.post(`/tasks`, {
        ...form,
        project_id: props.projectId
      })
      toast.success('Task created')
    }
    emit('saved')
    emit('close')
  } catch (e) {
    toast.error('Failed to save task')
  }
}
</script>
