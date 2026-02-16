import { createRouter, createWebHistory } from 'vue-router'
import Login from '../modules/auth/pages/Login.vue'
import Register from '../modules/auth/pages/Register.vue'
import Dashboard from '../modules/dashboard/pages/Dashboard.vue'
import NotFound from '../modules/common/pages/NotFound.vue'
// import { isMobileDevice } from '@/utils/device'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/register',
    name: 'Register',
    component: Register
  },
  {
    path: '/',
    name: 'dashboard',
    component: Dashboard
  },
  {
    path: '/desktop-blocked',
    name: 'desktop-blocked',
    // component: DesktopBlocked
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: NotFound
  }
]

// if (to.path === '/login' && !isMobileDevice()) {
//   return next({ name: 'desktop-blocked' })
// }

const router = createRouter({
  history: createWebHistory(),
  routes
})

// router.beforeEach((to, from, next) => {
//   if (to.name === 'login' && !isMobileDevice()) {
//     next({ name: 'desktop-blocked' })
//   } else {
//     next()
//   }
// })

export default router
