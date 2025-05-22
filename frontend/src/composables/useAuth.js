import { ref } from 'vue'
import api from '@/plugins/axios'
import router from '@/router'
import { useToast } from 'vue-toastification'

const toast = useToast()

const user = ref(JSON.parse(localStorage.getItem('user')) || null)

export function useAuth() {
    const login = async (form) => {
        const { data } = await api.post('/login', form)
        localStorage.setItem('access_token', data.access_token)
        localStorage.setItem('user', JSON.stringify(data.user))
        user.value = data.user
        toast.success(`👋 Welcome back, ${data.user.name}`)
        router.push('/dashboard')
    }

    const register = async (form) => {
        await api.post('/register', form)
        toast.success('🎉 Registered successfully!')
        router.push('/login')
    }

    const logout = async () => {
        await api.post('/logout')
        localStorage.removeItem('access_token')
        localStorage.removeItem('user')
        user.value = null
        router.push('/login')
    }

    const isAuthenticated = () => !!user.value

    return { user, login, register, logout, isAuthenticated }
}