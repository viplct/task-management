<template>
  <AuthLayout>
    <h1 class="text-2xl font-bold mb-4 text-center">Register</h1>
    <form @submit.prevent="handleRegister">
      <FormInput v-model="form.name" type="text" placeholder="Name" class="mb-2" />
      <FormInput v-model="form.email" type="email" placeholder="Email" class="mb-2" />
      <FormInput v-model="form.password" type="password" placeholder="Password" class="mb-2" />
      <FormInput v-model="form.password_confirmation" type="password" placeholder="Confirm Password" class="mb-4" />
      <button type="submit" class="w-full bg-green-600 text-white py-2 rounded">Register</button>
    </form>
    <p class="mt-4 text-sm text-center">
      Already have an account?
      <router-link to="/login" class="text-blue-600 underline">Login</router-link>
    </p>
  </AuthLayout>
</template>

<script setup>
import {reactive} from 'vue'
import {useAuth} from '@/composables/useAuth'
import FormInput from '@/components/FormInput.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'

const {register} = useAuth()

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const handleRegister = () => {
  register(form)
}
</script>
