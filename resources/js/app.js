import '../css/app.css';
import './bootstrap';
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

/* Vue Toast Notification */
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';

/* FontAwesome */
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { 
  faUsers, faUserPlus, faStethoscope, faBed, faHospital, faXRay, faHome, faClock,
  faPercent, faTicket, faCommentDots, faMagnifyingGlass, faUser, faArrowLeft, faWeightScale, faUserDoctor,
  faUserInjured, faCircleCheck, faCircleXmark, faHeartbeat, faHospitalUser, faProcedures, faMale, faFemale,
  faMars, faVenus, faEye, faEyeSlash, faCalendarDay, faCalendarDays, faSkull, faVolumeHigh, faPlus, faBell, faTimes
} from '@fortawesome/free-solid-svg-icons'

library.add(faUsers, faUserPlus, faStethoscope, faBed, faHospital, faXRay, faHome, faClock,
  faPercent, faTicket, faCommentDots, faMagnifyingGlass, faUser, faArrowLeft, faWeightScale, faUserDoctor,
  faBed, faUserInjured, faCircleCheck, faCircleXmark, faHeartbeat, faHospitalUser, faProcedures, faMale, faFemale,
  faMars, faVenus, faEye, faEyeSlash, faCalendarDay, faCalendarDays, faSkull, faVolumeHigh, faPlus, faBell, faTimes
)

const app = createApp(App)
app.use(router)
app.use(ToastPlugin);
app.component('font-awesome-icon', FontAwesomeIcon)
router.isReady().then(() => {
    app.mount('#app')

    const preloader = document.getElementById('preloader')

    if(preloader){
        preloader.style.opacity = '0'
        preloader.style.transition = 'opacity .45s ease'

        setTimeout(()=>{
            preloader.remove()
        }, 450)
    }
})

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/service-worker.js')
      .then(() => console.log('SW registered'))
      .catch(err => console.error('SW failed', err))
  })
}
