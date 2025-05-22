import axios from 'axios'
import { useToast } from 'vue-toastification'

const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    withCredentials: false,
})

api.interceptors.request.use(config => {
    const token = localStorage.getItem('access_token')
    if (token) config.headers.Authorization = `Bearer ${token}`
    return config
})

api.interceptors.response.use(
    response => response,
    error => {
        try {
            const toast = useToast()

            const message =
                error.response?.data?.message ||
                (error.response?.data?.errors
                    ? Object.values(error.response.data.errors).flat().join(' ')
                    : 'Something went wrong.')

            toast.error(message)
        } catch (e) {
            console.error('[Toast Error]', e)
        }

        return Promise.reject(error)
    }
)

export default api