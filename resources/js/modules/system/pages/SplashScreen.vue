<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import '../../../../css/splash.css'

const router = useRouter()
const status = ref('Menyiapkan aplikasi...')

const startApp = async () => {
    if(!navigator.onLine){
        status.value = 'Tidak ada koneksi internet'
        return
    }

    try{
        status.value = 'Menghubungi server...'
        await fetch('/health?'+Date.now(), {cache:'no-store'})
        status.value = 'Memuat sistem...'
        await new Promise(r=>setTimeout(r,1200))
        router.replace('/')
    }catch(e){
        status.value = 'Server tidak tersedia'
    }
}

onMounted(()=>{
    startApp()
})
</script>

<template>
<div class="splash-page">
    <div class="content">
        <img src="/public/pwa/icons/android-launchericon-192-192.png" class="logo"/>
        <h2 class="title">My App</h2>
        <p class="status">{{ status }}</p>
        <div class="spinner"></div>
    </div>
</div>
</template>
