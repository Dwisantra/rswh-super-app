import '../css/app.css';
import './bootstrap';
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

/* FontAwesome */
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUsers, faUserPlus, faStethoscope, faBed, faHospital, faXRay, faHome,
  faPercent, faTicket, faCommentDots, faMagnifyingGlass, faUser, faArrowLeft, faWeightScale
} from '@fortawesome/free-solid-svg-icons'

library.add(faUsers, faUserPlus, faStethoscope, faBed, faHospital, faXRay, faHome,
  faPercent, faTicket, faCommentDots, faMagnifyingGlass, faUser, faArrowLeft, faWeightScale
)

const app = createApp(App)
app.use(router)
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
