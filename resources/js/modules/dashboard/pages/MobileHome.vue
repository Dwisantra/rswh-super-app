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
        <RouterLink
          to="/profil"
          class="profile-btn control-height aspect-square place-items-center rounded-2xl bg-white text-teal-700"
        >
          <font-awesome-icon icon="user" />
        </RouterLink>
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

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/v1/me')

    if (data?.name) {
      displayName.value = data.name
    }
  } catch (_) {
    // fallback ke data snapshot jika profil belum tersedia
  }
})
</script>
