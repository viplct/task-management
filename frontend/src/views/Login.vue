<template>
  <AuthLayout>
    <h1 class="text-2xl font-bold mb-4 text-center">Login</h1>
    <form @submit.prevent="handleLogin">
      <FormInput v-model="form.email" type="email" placeholder="Email" class="mb-2" />
      <FormInput v-model="form.password" type="password" placeholder="Password" class="mb-4" />
      <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded">Login</button>
    </form>
    <p class="mt-4 text-sm text-center">
      Don't have an account?
      <router-link to="/register" class="text-blue-600 underline">Register here</router-link>
    </p>
  </AuthLayout>
</template>

<script setup>
import {reactive} from 'vue'
import {useAuth} from '@/composables/useAuth'
import FormInput from '@/components/FormInput.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'

const {login} = useAuth()

const form = reactive({
  email: '',
  password: ''
})

const handleLogin = () => {
  login(form)
}
</script>
