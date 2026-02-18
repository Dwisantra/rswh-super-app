<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <header class="bg-gradient-to-r from-teal-600 to-cyan-600 px-4 pb-8 pt-7 text-white">
      <div class="header-top mb-4">
        <RouterLink to="/" class="back-btn">
          <font-awesome-icon icon="arrow-left" />
        </RouterLink>

        <h1 class="header-title">Jadwal Dokter</h1>
      </div>
      <p class="text-sm text-cyan-100">Filter per tanggal dan klinik.</p>
    </header>

    <main class="space-y-3 px-4 pt-4">
      <section class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
        <p class="mb-2 text-sm font-medium text-slate-700">Filter Jadwal</p>

        <div class="grid grid-cols-1 gap-2">
          <input v-model="selectedDate" type="date" class="rounded-xl border border-slate-200 px-3 py-2 text-sm" />

          <select v-model="selectedClinic" class="rounded-xl border border-slate-200 px-3 py-2 text-sm">
            <option value="">Semua Klinik</option>
            <option v-for="clinic in uniqueClinics" :key="clinic" :value="clinic">{{ clinic }}</option>
          </select>
        </div>
      </section>

      <article
        v-for="item in filteredSchedules"
        :key="item.id"
        class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
      >
        <p class="text-base font-semibold text-slate-900">{{ item.doctor }}</p>
        <p class="mt-1 text-sm text-slate-600">{{ formatDate(item.date) }} · {{ item.time }}</p>
        <div class="mt-2 flex flex-wrap gap-2 text-xs">
          <span class="rounded-full bg-cyan-50 px-2.5 py-1 text-cyan-700">{{ item.clinic }}</span>
        </div>
      </article>

      <article v-if="filteredSchedules.length === 0" class="rounded-2xl border border-slate-200 bg-white p-4 text-sm text-slate-500 shadow-sm">
        Tidak ada jadwal sesuai filter.
      </article>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import { doctorSchedules } from '../data/mobileDashboardData'

const selectedDate = ref('')
const selectedClinic = ref('')

const uniqueClinics = [...new Set(doctorSchedules.map((item) => item.clinic))]

const filteredSchedules = computed(() =>
  doctorSchedules.filter((item) => {
    const byDate = selectedDate.value ? item.date === selectedDate.value : true
    const byClinic = selectedClinic.value ? item.clinic === selectedClinic.value : true
    return byDate && byClinic
  })
)

const formatDate = (value) => new Date(value).toLocaleDateString('id-ID', {
  weekday: 'long',
  day: '2-digit',
  month: 'long',
  year: 'numeric'
})
</script>
