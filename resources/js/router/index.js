import { createRouter, createWebHistory } from 'vue-router'
import Login from '../modules/auth/pages/Login.vue'
import Dashboard from '../modules/dashboard/pages/Dashboard.vue'
import NotFound from '../modules/common/pages/NotFound.vue'

const routes = [
  {
    path: '/login',
    component: Login
  },
  {
    path: '/',
    component: Dashboard
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: NotFound
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
