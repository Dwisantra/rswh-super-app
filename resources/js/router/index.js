import { createRouter, createWebHistory } from 'vue-router'
import Login from '../modules/auth/pages/Login.vue'
import Register from '../modules/auth/pages/Register.vue'
import MobileHome from '../modules/dashboard/pages/MobileHome.vue'
import QueueTicket from '../modules/dashboard/pages/QueueTicket.vue'
import BmiCalculator from '../modules/dashboard/pages/BmiCalculator.vue'
import BedAvailability from '../modules/dashboard/pages/BedAvailability.vue'
import MobilePlaceholder from '../modules/dashboard/pages/MobilePlaceholder.vue'
import NotFound from '../modules/common/pages/NotFound.vue'

const routes = [
  { path: '/login', name: 'login', component: Login, meta: { guestOnly: true } },
  { path: '/register', name: 'register', component: Register, meta: { guestOnly: true } },
  { path: '/', name: 'mobile-home', component: MobileHome },
  { path: '/ticket', name: 'queue-ticket', component: QueueTicket },
  { path: '/bmi', name: 'bmi-calculator', component: BmiCalculator },
  { path: '/tempat-tidur', name: 'bed-availability', component: BedAvailability },
  { path: '/promo', name: 'promo', component: MobilePlaceholder, meta: { title: 'Promo' } },
  { path: '/chat', name: 'chat', component: MobilePlaceholder, meta: { title: 'Chat' } },
  { path: '/:pathMatch(.*)*', name: 'not-found', component: NotFound }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to) => {
  const token = localStorage.getItem('auth_token')

  if (to.meta.guestOnly && token) {
    return { name: 'mobile-home' }
  }

  return true
})

export default router
