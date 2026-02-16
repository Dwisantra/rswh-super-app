<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <header class="bg-gradient-to-r from-teal-600 to-cyan-600 px-4 pb-8 pt-7 text-white">
      <RouterLink to="/" class="text-2xl">←</RouterLink>
      <h1 class="mt-3 text-2xl font-bold">Jadwal Dokter</h1>
      <p class="text-sm text-cyan-100">Filter per hari dan ruangan/poli.</p>
    </header>

    <main class="space-y-3 px-4 pt-4">
      <section class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
        <p class="mb-2 text-sm font-medium text-slate-700">Hari</p>
        <div class="flex gap-2 overflow-auto pb-1">
          <button
            v-for="day in dayFilters"
            :key="day"
            class="whitespace-nowrap rounded-full border px-3 py-1.5 text-sm"
            :class="selectedDay === day ? 'border-teal-600 bg-teal-600 text-white' : 'border-slate-200 text-slate-600'"
            @click="selectedDay = day"
          >
            {{ day }}
          </button>
        </div>

        <div class="mt-3 grid grid-cols-2 gap-2">
          <select v-model="selectedPoli" class="rounded-xl border border-slate-200 px-3 py-2 text-sm">
            <option value="">Semua Poli</option>
            <option v-for="poli in uniquePoli" :key="poli" :value="poli">{{ poli }}</option>
          </select>

          <select v-model="selectedRoom" class="rounded-xl border border-slate-200 px-3 py-2 text-sm">
            <option value="">Semua Ruangan</option>
            <option v-for="room in uniqueRooms" :key="room" :value="room">{{ room }}</option>
          </select>
        </div>
      </section>

      <article
        v-for="item in filteredSchedules"
        :key="item.id"
        class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
      >
        <p class="text-base font-semibold text-slate-900">{{ item.doctor }}</p>
        <p class="mt-1 text-sm text-slate-600">{{ item.day }} · {{ item.time }}</p>
        <div class="mt-2 flex flex-wrap gap-2 text-xs">
          <span class="rounded-full bg-cyan-50 px-2.5 py-1 text-cyan-700">{{ item.poli }}</span>
          <span class="rounded-full bg-teal-50 px-2.5 py-1 text-teal-700">{{ item.room }}</span>
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
import { dayFilters, doctorSchedules } from '../data/mobileDashboardData'

const selectedDay = ref(dayFilters[0])
const selectedPoli = ref('')
const selectedRoom = ref('')

const uniquePoli = [...new Set(doctorSchedules.map((item) => item.poli))]
const uniqueRooms = [...new Set(doctorSchedules.map((item) => item.room))]

const filteredSchedules = computed(() =>
  doctorSchedules.filter((item) => {
    const byDay = item.day === selectedDay.value
    const byPoli = selectedPoli.value ? item.poli === selectedPoli.value : true
    const byRoom = selectedRoom.value ? item.room === selectedRoom.value : true
    return byDay && byPoli && byRoom
  })
)
</script>
