<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <section class="sticky top-0 z-50 rounded-b-[2rem] bg-gradient-to-br from-teal-600 to-cyan-600 px-4 pb-6 pt-7 text-white shadow-lg">
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

    <main class="space-y-4 px-4 pt-4">
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
            <p class="text-xl font-bold text-slate-900">{{ activePatient?.norm || '-' }}</p>
            <p class="text-xs font-bold text-teal-600 mt-1 uppercase">{{ activePatient?.name }}</p>
          </div>
          
          <button 
            @click="isSelectorOpen = true"
            class="rounded-full bg-teal-50 px-3 py-1 text-xs font-bold text-teal-700 border border-teal-100 active:scale-95 transition-transform"
          >
            Ganti Pasien
          </button>
        </div>
        
        <PullToRefresh v-model="refreshing" @refresh="onRefresh">
          <div class="mt-3">
            <CarouselCard :items="schedules">
              <template #default="{ item }">
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm mx-1">
                  <div class="flex justify-between items-start">
                    <div class="space-y-1">
                      <span class="inline-block px-2 py-0.5 rounded-full bg-teal-50 text-[10px] font-bold text-teal-700 uppercase tracking-wider">
                        Jadwal Terdekat
                      </span>
                      <h3 class="text-base font-bold text-slate-800">{{ item.klinik }}</h3>
                      <p class="text-sm text-slate-600">{{ item.dokter }}</p>
                      
                      <div class="flex items-center gap-3 mt-2 text-xs text-slate-500">
                        <div class="flex items-center gap-1">
                          <font-awesome-icon icon="calendar" class="text-teal-500" />
                          <span>{{ item.hari }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                          <font-awesome-icon icon="clock" class="text-teal-500" />
                          <span>{{ item.jam }}</span>
                        </div>
                      </div>
                    </div>
                    
                    <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400">
                      <font-awesome-icon icon="chevron-right" />
                    </div>
                  </div>
                </div>
              </template>
            </CarouselCard>
          </div>
        </PullToRefresh>
      </article>

      <PatientSelector 
        v-model="isSelectorOpen"
        :family-members="familyMembers"
        :selected-id="activePatient?.id"
        @onSelect="changeActivePatient"
      />

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
    <MobileBottomNav />
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import axios from 'axios'
import Pusher from 'pusher-js'
import PullToRefresh from '../components/PullToRefresh.vue'
import CarouselCard from '../components/CarouselCard.vue'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import PhotoCarousel from '../components/PhotoCarousel.vue'
import ServiceMenuGrid from '../components/ServiceMenuGrid.vue'
import PatientSelector from '../components/PatientSelector.vue'
import { homeBanners, menuSections, promos } from '../data/mobileDashboardData'

// State
const displayName = ref('User')
const searchQuery = ref('')
const familyMembers = ref([])
const activePatient = ref(null)
const isSelectorOpen = ref(false)
const notifications = ref([])
const refreshing = ref(false)

// Config Pusher
const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;
const pusherCluster = import.meta.env.VITE_PUSHER_APP_CLUSTER;

// Notifikasi Logic
const unreadCount = computed(() => notifications.value.filter(n => n.read_at === null).length)

const onRefresh = async () => {
  try {
    await fetchInitialData()
  } finally {
    refreshing.value = false
  }
}

const fetchInitialData = async () => {
  try {
    const res = await axios.get('/api/v1/patients/family')
    familyMembers.value = res.data
    activePatient.value = res.data.find(p => p.is_default) || res.data[0]
  } catch (e) { 
    console.error("Gagal memuat data keluarga", e) 
  }
}

const changeActivePatient = (patient) => {
  activePatient.value = patient
}

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
        return item.label.toLowerCase().includes(keyword) || 
               item.subtitle.toLowerCase().includes(keyword)
      })
    }))
    .filter((section) => section.items.length > 0)
})

onMounted(async () => {
  try {
    fetchSchedules()
    // 1. Load User Profile
    const userRes = await axios.get('/api/v1/me')
    if (userRes.data?.name) displayName.value = userRes.data.name

    // 2. Load Notifications
    const notifRes = await axios.get('/api/v1/notifications/history')
    notifications.value = notifRes.data.data

    // 3. Load Family & Active Patient
    await fetchInitialData()

    // 4. Setup Pusher
    const pusher = new Pusher(pusherKey, { cluster: pusherCluster });
    const broadcastChannel = pusher.subscribe('broadcast-channel');
    broadcastChannel.bind('new-announcement', (data) => {
        notifications.value.unshift({ ...data, read_at: null });
    });

    if (userRes.data?.id) {
        const personalChannel = pusher.subscribe('user-channel-' + userRes.data.id);
        personalChannel.bind('new-notification', (data) => {
            notifications.value.unshift({ ...data, read_at: null });
        });
    }
  } catch (error) {
    console.error("Initialization error:", error)
  }
})


// dummy
const schedules = ref([])
const loadingSchedules = ref(false)
const fetchSchedules = async () => {
  loadingSchedules.value = true
  
  // Simulasi loading 1 detik
  await new Promise(resolve => setTimeout(resolve, 1000))

  // Data Statis
  schedules.value = [
    { 
      id: 1, 
      klinik: 'Klinik Penyakit Dalam', 
      dokter: 'dr. Bambang Spesialis PD', 
      hari: 'Senin', 
      jam: '08:00 - 12:00' 
    },
    { 
      id: 2, 
      klinik: 'Klinik Anak (Pediatri)', 
      dokter: 'dr. Anisa Sp.A', 
      hari: 'Selasa', 
      jam: '10:00 - 14:00' 
    },
    { 
      id: 3, 
      klinik: 'Klinik Gigi & Mulut', 
      dokter: 'drg. Citra Permata', 
      hari: 'Rabu', 
      jam: '13:00 - 16:00' 
    }
  ]
  
  loadingSchedules.value = false
}
</script>
