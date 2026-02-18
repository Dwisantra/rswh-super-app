<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <header class="app-header">
      <div class="header-top">
        <RouterLink to="/" class="back-btn">
          <font-awesome-icon icon="arrow-left" />
        </RouterLink>

        <h1 class="header-title">Profil Pasien</h1>
      </div>

      <p class="header-subtitle">
        Data profil (dummy) untuk tampilan aplikasi.
      </p>
    </header>

    <main class="space-y-3 px-4 pt-4">
      <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <p class="text-xs text-slate-500">Nama</p>
        <p class="text-lg font-semibold text-slate-900">{{ profile.name }}</p>
      </article>
      <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <p class="text-xs text-slate-500">No. Rekam Medis</p>
        <p class="text-lg font-semibold text-slate-900">{{ profile.mrn }}</p>
      </article>
      <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <p class="text-xs text-slate-500">NIK</p>
        <p class="text-lg font-semibold text-slate-900">{{ profile.nik }}</p>
      </article>
      <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <p class="text-xs text-slate-500">No. HP</p>
        <p class="text-lg font-semibold text-slate-900">{{ profile.phone }}</p>
      </article>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import MobileBottomNav from '../components/MobileBottomNav.vue'

const profile = {
  name: 'Okvi Dwi Santra',
  mrn: '000123456',
  nik: '3175xxxxxxxxxxxx',
  phone: '0812-3456-7890'
}
</script>
