import { createRouter, createWebHistory } from 'vue-router'
import Login from '../modules/auth/pages/Login.vue'
import Register from '../modules/auth/pages/Register.vue'
import Dashboard from '../modules/dashboard/pages/Dashboard.vue'
import NotFound from '../modules/common/pages/NotFound.vue'

const routes = [
  { path: '/login', name: 'login', component: Login, meta: { guestOnly: true } },
  { path: '/register', name: 'register', component: Register, meta: { guestOnly: true } },
  { path: '/', name: 'dashboard', component: Dashboard, meta: { requiresAuth: true } },
  { path: '/:pathMatch(.*)*', name: 'not-found', component: NotFound }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to) => {
  const token = localStorage.getItem('auth_token')
  if (to.meta.requiresAuth && !token) {
    return { name: 'login' }
  }

  if (to.meta.guestOnly && token) {
    return { name: 'dashboard' }
  }

  return true
})

export default router
