<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <header class="bg-gradient-to-r from-teal-600 to-cyan-600 px-4 pb-8 pt-7 text-white">
      <RouterLink to="/" class="text-2xl">←</RouterLink>
      <h1 class="mt-3 text-2xl font-bold">Cari Layanan</h1>
      <p class="text-sm text-cyan-100">Pilih layanan yang ingin Anda akses.</p>
    </header>

    <main class="space-y-3 px-4 pt-4">
      <RouterLink
        v-for="item in quickLinks"
        :key="item.to"
        :to="item.to"
        class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
      >
        <div>
          <p class="text-base font-semibold text-slate-900">{{ item.label }}</p>
          <p class="text-sm text-slate-500">{{ item.subtitle }}</p>
        </div>
        <span class="text-slate-400">›</span>
      </RouterLink>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import MobileBottomNav from '../components/MobileBottomNav.vue'

const quickLinks = [
  { label: 'Jadwal Dokter', subtitle: 'Lihat jadwal per hari dan ruangan/poli', to: '/jadwal-dokter' },
  { label: 'Tiket Antrian', subtitle: 'Lihat antrian aktif Anda', to: '/ticket' },
  { label: 'Kalkulator BMI', subtitle: 'Hitung BMI dengan cepat', to: '/bmi' },
  { label: 'Ketersediaan Tempat Tidur', subtitle: 'Cek kapasitas kamar', to: '/tempat-tidur' }
]
</script>
