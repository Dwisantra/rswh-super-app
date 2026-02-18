<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import '../../../../css/splash.css'

const router = useRouter()
const route = useRoute()
const status = ref('Menyiapkan aplikasi...')
const isChecking = ref(true)
const isOnline = ref(navigator.onLine)
const logoUrl = '/pwa/icons/android-launchericon-192-192.png'

const updateOnlineStatus = () => {
    isOnline.value = navigator.onLine

    if (!isOnline.value) {
        status.value = 'Tidak ada koneksi internet.'
        isChecking.value = false
    }
}

const checkServer = async () => {
    if (!navigator.onLine) {
        updateOnlineStatus()
        return
    }

    isChecking.value = true
    status.value = 'Menghubungi server...'

    try {
        const response = await fetch(`/health?${Date.now()}`, {
            method: 'GET',
            cache: 'no-store'
        })

        if (!response.ok) {
            throw new Error('Server check failed')
        }
        status.value = 'Memuat sistem...'
        sessionStorage.setItem('splash_seen', '1')

        await new Promise((resolve) => setTimeout(resolve, 800))

        const redirectPath = typeof route.query.redirect === 'string' && route.query.redirect
            ? route.query.redirect
            : '/'

        router.replace(redirectPath)
    } catch {
        status.value = 'Server tidak terhubung. Coba lagi.'
        isChecking.value = false
    }
}

onMounted(() => {
    window.addEventListener('online', updateOnlineStatus)
    window.addEventListener('offline', updateOnlineStatus)

    checkServer()
})

onUnmounted(() => {
    window.removeEventListener('online', updateOnlineStatus)
    window.removeEventListener('offline', updateOnlineStatus)
})
</script>

<template>
    <div class="splash-page">
        <div class="splash-content">
            <img :src="logoUrl" class="splash-logo" alt="Logo aplikasi" />
            <h2 class="splash-title">RSWH Super App</h2>
            <p class="splash-status">{{ status }}</p>

            <div v-if="isChecking" class="splash-spinner" aria-hidden="true"></div>

            <button
                v-else
                type="button"
                class="splash-retry"
                :disabled="!isOnline"
                @click="checkServer"
            >
                {{ isOnline ? 'Coba Lagi' : 'Menunggu Koneksi...' }}
            </button>
        </div>
    </div>
</template>
