import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/views/Login.vue'
import Register from '@/views/Register.vue'
import Dashboard from '@/views/Dashboard.vue'
import ProjectIndex from "@/views/projects/ProjectIndex.vue";

const routes = [
    { path: '/', redirect: '/login' }, // 🔥 redirect root to login
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/projects', component: ProjectIndex, meta: { requiresAuth: true } },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('access_token')
    if (to.meta.requiresAuth && !token) {
        return next('/login')
    }
    next()
})

export default router
