<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
     <PageHeader
      title="Profil Pasien"
      description="Data profil akun pasien."
      backTo="/"
    />

    <main class="space-y-3 px-4 pt-4">
      <RouterLink
        to="/profil/detail"
        class="block rounded-2xl border border-slate-200 bg-white p-4 text-sm font-semibold text-teal-700 shadow-sm"
      >
        <font-awesome-icon icon="user"/> Lihat Detail Pengguna
      </RouterLink>

      <RouterLink
        to="/profil/notifikasi"
        class="block rounded-2xl border border-slate-200 bg-white p-4 text-sm font-semibold text-teal-700 shadow-sm"
      >
        <font-awesome-icon icon="user"/> Lihat Detail Pengguna
        Lihat Riwayat Notifikasi
      </RouterLink>

      <article class="rounded-2xl border border-teal-100 bg-gradient-to-br from-teal-50 to-cyan-50 p-4 shadow-sm">
        <p class="text-sm font-semibold text-slate-900">Notifikasi</p>
        <p class="mt-1 text-xs text-slate-600">
          Aktifkan notifikasi agar info antrian, jadwal dokter, dan pengingat layanan bisa dikirim kapan saja oleh admin/sistem pihak ketiga.
        </p>

        <p class="mt-3 text-xs font-medium text-slate-700">Status izin: {{ permissionLabel }}</p>

        <p class="mt-1 text-[11px] text-slate-500">
          Untuk aplikasi Android berbasis TWA, popup permission akan muncul saat aplikasi pertama dibuka (jika belum pernah izin).
        </p>

        <button
          type="button"
          class="mt-3 w-full rounded-xl bg-teal-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-teal-700 disabled:cursor-not-allowed disabled:opacity-70"
          :disabled="isRequestingPermission || !isNotificationAvailable"
          @click="activateNotifications"
        >
          {{ isRequestingPermission ? 'Memproses...' : 'Aktifkan Notifikasi' }}
        </button>

        <p v-if="notificationMessage" class="mt-3 text-xs text-slate-700">{{ notificationMessage }}</p>
      </article>

      <button
        type="button"
        class="mt-2 w-full rounded-xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-rose-700 disabled:cursor-not-allowed disabled:opacity-70"
        :disabled="isLoggingOut"
        @click="logout"
      >
        {{ isLoggingOut ? 'Keluar...' : 'Logout' }}
      </button>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import PageHeader from '../components/Header.vue'
// import { userProfile } from '../data/userProfileData'
import {
  activateNotificationChannel,
  getNotificationPermission,
  isNotificationSupported,
  isPushSupported,
  isVapidConfigured
} from '@/services/notifications'

const router = useRouter()
const isLoggingOut = ref(false)
const isRequestingPermission = ref(false)
const notificationMessage = ref('')
const permissionStatus = ref('default')
const isNotificationAvailable = isNotificationSupported()
const isPushApiAvailable = isPushSupported()
// const profile = userProfile

const permissionLabel = computed(() => {
  if (!isNotificationAvailable) return 'Perangkat tidak mendukung notifikasi'

  if (permissionStatus.value === 'granted') {
    if (!isPushApiAvailable) return 'Diizinkan (tanpa Push API)'
    return 'Diizinkan'
  }

  if (permissionStatus.value === 'denied') return 'Ditolak'

  return 'Belum diminta'
})

const activateNotifications = async () => {
  if (!isNotificationAvailable) {
    notificationMessage.value = 'Browser/perangkat Anda belum mendukung notifikasi.'
    return
  }

  isRequestingPermission.value = true
  notificationMessage.value = ''

  try {
    const result = await activateNotificationChannel()
    permissionStatus.value = getNotificationPermission()

    if (result.subscriptionSent) {
      notificationMessage.value = 'Notifikasi aktif. Admin/sistem dapat mengirim push kapan saja.'
      return
    }

    if (!result.pushSupported) {
      notificationMessage.value = 'Izin notifikasi aktif, tetapi Push API belum didukung perangkat ini.'
      return
    }

    notificationMessage.value = result.vapidConfigured
      ? 'Izin notifikasi aktif. Menunggu pengiriman dari admin/sistem.'
      : 'Izin notifikasi aktif. Konfigurasi server push (VAPID) belum aktif.'
  } catch (error) {
    notificationMessage.value = error?.message || 'Gagal mengaktifkan notifikasi.'
    permissionStatus.value = getNotificationPermission()
  } finally {
    isRequestingPermission.value = false
  }
}

const logout = async () => {
  isLoggingOut.value = true

  try {
    await axios.post('/api/v1/logout')
  } catch (_) {
    // abaikan jika API logout gagal
  }

  localStorage.removeItem('auth_token')
  delete axios.defaults.headers.common.Authorization
  await router.push('/login')
  isLoggingOut.value = false
}

onMounted(() => {
  permissionStatus.value = getNotificationPermission()
})
</script>
