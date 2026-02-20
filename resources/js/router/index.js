import { createRouter, createWebHistory } from 'vue-router'
import Login from '../modules/auth/pages/Login.vue'
import Register from '../modules/auth/pages/Register.vue'
import MobileHome from '../modules/dashboard/pages/MobileHome.vue'
import QueueTicket from '../modules/dashboard/pages/QueueTicket.vue'
import BmiCalculator from '../modules/dashboard/pages/BmiCalculator.vue'
import BedAvailability from '../modules/dashboard/pages/BedAvailability.vue'
import DoctorSchedule from '../modules/dashboard/pages/DoctorSchedule.vue'
import PatientOperationSchedule from '../modules/dashboard/pages/PatientOperationSchedule.vue'
import QueueMonitor from '../modules/dashboard/pages/QueueMonitor.vue'
import FamilyMembers from '../modules/dashboard/pages/FamilyMembers.vue'
import AddFamilyMember from '../modules/dashboard/pages/AddFamilyMember.vue'
import NewPatientRegistration from '../modules/dashboard/pages/NewPatientRegistration.vue'
import RegistrationPage from '../modules/dashboard/pages/RegistrationPage.vue'
import ProfilePage from '../modules/dashboard/pages/ProfilePage.vue'
import PromoPage from '../modules/dashboard/pages/PromoPage.vue'
import MobilePlaceholder from '../modules/dashboard/pages/MobilePlaceholder.vue'
import NotFound from '../modules/common/pages/NotFound.vue'
import SplashScreen from '../modules/system/pages/SplashScreen.vue'

const routes = [
  { path: '/splash', name: 'splash', component: SplashScreen },
  { path: '/login', name: 'login', component: Login, meta: { guestOnly: true } },
  { path: '/register', name: 'register', component: Register, meta: { guestOnly: true } },
  { path: '/', name: 'mobile-home', component: MobileHome },
  { path: '/profil', name: 'profile', component: ProfilePage },
  { path: '/keluarga', name: 'family-members', component: FamilyMembers },
  { path: '/keluarga/tambah', name: 'family-add', component: AddFamilyMember },
  { path: '/pasien-baru', name: 'new-patient-registration', component: NewPatientRegistration },
  { path: '/pendaftaran', name: 'registration', component: RegistrationPage },
  { path: '/ticket', name: 'queue-ticket', component: QueueTicket },
  { path: '/jadwal-dokter', name: 'doctor-schedule', component: DoctorSchedule },
  { path: '/jadwal-operasi-pasien', name: 'patient-operation-schedule', component: PatientOperationSchedule },
  { path: '/pantau-antrian', name: 'queue-monitor', component: QueueMonitor },
  { path: '/bmi', name: 'bmi-calculator', component: BmiCalculator },
  { path: '/tempat-tidur', name: 'bed-availability', component: BedAvailability },
  { path: '/penunjang', name: 'penunjang', component: MobilePlaceholder, meta: { title: 'Penunjang' }},
  { path: '/promo', name: 'promo', component: PromoPage },
  { path: '/chat', name: 'chat', component: MobilePlaceholder, meta: { title: 'Chat' } },
  { path: '/:pathMatch(.*)*', name: 'not-found', component: NotFound }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to) => {
  if (to.name !== 'splash' && !sessionStorage.getItem('splash_seen')) {
    return {
      name: 'splash',
      query: {
        redirect: to.fullPath
      }
    }
  }
  
  const token = localStorage.getItem('auth_token')

  if (to.meta.guestOnly && token) {
    return { name: 'mobile-home' }
  }

  return true
})

export default router
