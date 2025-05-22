<template>
  <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white p-6 w-full max-w-md rounded shadow">
      <h2 class="text-lg font-bold mb-4">{{ project?.id ? 'Edit Project' : 'New Project' }}</h2>
      <form @submit.prevent="submit">
        <input v-model="form.title" placeholder="Title" class="w-full mb-3 p-2 border rounded" />
        <textarea v-model="form.description" placeholder="Description" class="w-full mb-3 p-2 border rounded" />
        <select v-model="form.status" class="w-full mb-3 p-2 border rounded">
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
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

const props = defineProps({ project: Object })
const emit = defineEmits(['close', 'saved'])

const toast = useToast()

const form = reactive({
  title: '',
  description: '',
  status: 'active',
})

watch(() => props.project, (project) => {
  if (project) Object.assign(form, project)
  else Object.assign(form, { title: '', description: '', status: 'active' })
}, { immediate: true })

const submit = async () => {
  try {
    if (props.project?.id) {
      await api.put(`/projects/${props.project.id}`, form)
      toast.success('Project updated')
    } else {
      await api.post('/projects', form)
      toast.success('Project created')
    }
    emit('saved')
    emit('close')
  } catch (e) {
    console.error(e)
  }
}
</script>
