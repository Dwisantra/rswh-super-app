<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <section class="rounded-b-[2rem] bg-gradient-to-br from-teal-600 to-cyan-600 px-4 pb-6 pt-7 text-white shadow-lg">
      <div class="flex items-center gap-3">
        <div class="search-box">
          <font-awesome-icon icon="magnifying-glass" class="search-icon" />
          <input
            v-model.trim="searchQuery"
            type="text"
            placeholder="Cari layanan kesehatan"
            class="search-input"
          />
        </div>
        <div class="flex items-center gap-2">
          <RouterLink
            to="/profil/notifikasi"
            class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-teal-700 shadow-sm"
            @click="handleBellClick"
          >
              <font-awesome-icon icon="bell" />
              <span v-if="unreadCount > 0" class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white border-2 border-white">
                  {{ unreadCount }}
              </span>
          </RouterLink>

          <RouterLink to="/profil" class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-teal-700 shadow-sm">
              <font-awesome-icon icon="user" />
          </RouterLink>
      </div>
      </div>

      <article class="mt-4 rounded-2xl bg-white/15 p-4 backdrop-blur-sm">
        <div class="flex items-center gap-2">
          <p class="text-sm text-cyan-50">
            {{ greetingMessage }}, {{ displayName }}
          </p>
          <span class="animate-wave text-lg">
            <template v-if="greetingMessage.includes('pagi')">☀️</template>
            <template v-else-if="greetingMessage.includes('siang')">🌤️</template>
            <template v-else-if="greetingMessage.includes('sore')">🌅</template>
            <template v-else>🌙</template>
          </span>
        </div>
      </article>
    </section>

    <main class="-mt-3 space-y-4 px-4">
      <PhotoCarousel :slides="homeBanners" />

      <!-- <main class="-mt-3 space-y-4 px-4">
        <section v-if="notifications.length > 0" class="space-y-3">
          <div class="flex items-center justify-between">
            <h2 class="text-ml font-bold text-slate-900">Notifikasi Terbaru</h2>
          </div>
          <article v-for="item in notifications.slice(0, 1)" :key="item.id" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-sm font-semibold text-slate-900">{{ item.title }}</p>
            <p class="text-xs text-slate-600 line-clamp-1">{{ item.body }}</p>
          </article>
        </section>
      </main> -->

      <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex items-start justify-between gap-3">
          <div>
            <p class="text-sm text-slate-500">No. Rekam Medis</p>
            <p class="text-xl font-bold text-slate-900">{{ patientSnapshot.mrn }}</p>
          </div>
          <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
        </div>
        <div class="mt-3 rounded-xl bg-slate-50 p-3">
          <p class="text-xs text-slate-500">Jadwal terdekat</p>
          <p class="text-base font-semibold text-slate-800">{{ patientSnapshot.clinic }}</p>
          <p class="text-sm text-slate-600">{{ patientSnapshot.nextVisit }}</p>
        </div>
      </article>

      <ServiceMenuGrid :sections="filteredSections" />

      <section class="space-y-3 pb-2">
        <div class="flex items-center justify-between">
          <h2 class="text-ml font-bold text-slate-900">Info & Promo Kesehatan</h2>
          <RouterLink to="/promo" class="text-sm font-semibold text-teal-700">Lihat semua</RouterLink>
        </div>
        <article v-for="promo in promos" :key="promo.id" class="rounded-2xl border border-teal-100 bg-gradient-to-r from-teal-50 to-cyan-50 p-4">
          <p class="text-lg font-semibold text-slate-900">{{ promo.title }}</p>
          <p class="mt-1 text-sm text-slate-600">{{ promo.subtitle }}</p>
          <RouterLink :to="promo.to" class="mt-3 inline-block rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white">{{ promo.cta }}</RouterLink>
        </article>
      </section>
    </main>

    <Transition name="fade-slide">
      <div v-if="showPopup" class="fixed top-5 left-4 right-4 z-[9999] pointer-events-none">
        <div class="mx-auto max-w-sm rounded-2xl border-l-4 border-teal-500 bg-white p-4 shadow-xl ring-1 ring-black/5 pointer-events-auto">
          <div class="flex items-start gap-3">
            <div class="flex-1">
              <p class="text-sm font-bold text-slate-900">{{ popupData.title }}</p>
              <p class="text-xs text-slate-600 line-clamp-2">{{ popupData.body }}</p>
            </div>
            <button @click="showPopup = false" class="text-slate-400 hover:text-slate-600">
              <font-awesome-icon icon="times" />
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import axios from 'axios'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import PhotoCarousel from '../components/PhotoCarousel.vue'
import ServiceMenuGrid from '../components/ServiceMenuGrid.vue'
import { homeBanners, menuSections, patientSnapshot, promos } from '../data/mobileDashboardData'
import Pusher from 'pusher-js';

// notifikasi
const notifications = ref([])
const unreadCount = computed(() => {
    return notifications.value.filter(n => n.read_at === null).length
})
const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;
const pusherCluster = import.meta.env.VITE_PUSHER_APP_CLUSTER;

// poup notifikasi
const showPopup = ref(false);
const popupData = ref({ title: '', body: '' });

const searchQuery = ref('')
const displayName = ref(patientSnapshot.name)

const greetingMessage = computed(() => {
  const hour = new Date().getHours()

  if (hour >= 4 && hour < 11) return 'Selamat pagi'
  if (hour >= 11 && hour < 15) return 'Selamat siang'
  if (hour >= 15 && hour < 18) return 'Selamat sore'

  return 'Selamat malam'
})

const filteredSections = computed(() => {
  if (!searchQuery.value) return menuSections

  const keyword = searchQuery.value.toLowerCase()

  return menuSections
    .map((section) => ({
      ...section,
      items: section.items.filter((item) => {
        const label = item.label.toLowerCase()
        const subtitle = item.subtitle.toLowerCase()
        return label.includes(keyword) || subtitle.includes(keyword)
      })
    }))
    .filter((section) => section.items.length > 0)
})

const fetchNotifications = async () => {
    try {
        const res = await axios.get('/api/v1/notifications/history')
        notifications.value = res.data.data
    } catch (e) {
        console.error("Gagal memuat riwayat");
    }
}

const handleBellClick = async () => {
    try {
        await axios.post('/api/v1/notifications/mark-as-read')
        notifications.value.forEach(n => n.read_at = new Date())
    } catch (e) {
        console.error("Gagal update status baca")
    }
}

const triggerPopup = (data) => {
  popupData.value = data;
  showPopup.value = true;

  setTimeout(() => {
    showPopup.value = false;
  }, 5000);
};

const urlBase64ToUint8Array = (base64String) => {
  const padding = '='.repeat((4 - base64String.length % 4) % 4);
  const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
  const rawData = window.atob(base64);
  return Uint8Array.from([...rawData].map((char) => char.charCodeAt(0)));
};

const subscribeUser = async () => {
  try {
    const registration = await navigator.serviceWorker.ready;
    
    // Gunakan Public Key yang baru Anda buat
    const publicKey = import.meta.env.VITE_VAPID_PUBLIC_KEY;
    
    const subscription = await registration.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: urlBase64ToUint8Array(publicKey)
    });

    // Simpan ke database Laravel
    await axios.post('/api/v1/push-subscribe', subscription);
    console.log('Berhasil Subscribe Notifikasi Sistem');
  } catch (err) {
    console.error('Gagal subscribe:', err);
  }
};

onMounted(async () => {
  try {
    const userRes = await axios.get('/api/v1/me')
    const userData = userRes.data
    if (userData?.name) displayName.value = userData.name

    fetchNotifications()

    const pusher = new Pusher(pusherKey, {
        cluster: pusherCluster
    });

    // --- CHANNEL 1: Semua User ---
    const broadcastChannel = pusher.subscribe('broadcast-channel');
    broadcastChannel.bind('new-announcement', (data) => {
        notifications.value.unshift({ ...data, read_at: null });
        triggerPopup(data);
    });

    // --- CHANNEL 2: Per User ---
    if (userData?.id) {
        const personalChannel = pusher.subscribe('user-channel-' + userData.id);
        personalChannel.bind('new-notification', (data) => {
            notifications.value.unshift({ ...data, read_at: null });
            triggerPopup(data);
        });
    }

    await axios.post('/api/v1/notifications/mark-as-read')

  } catch (error) {
    // console.error("Error initialization:", error)
  }
})
</script>
