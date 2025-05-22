import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/views/Login.vue'
import Register from '@/views/Register.vue'
import Dashboard from '@/views/Dashboard.vue'

const routes = [
    { path: '/', redirect: '/login' }, // 🔥 redirect root to login
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    { path: '/dashboard', component: Dashboard },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
