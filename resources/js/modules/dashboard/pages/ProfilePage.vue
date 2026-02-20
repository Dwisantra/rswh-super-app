<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <PageHeader 
      title="Profil Pasien" 
      description="Data profil (dummy) untuk tampilan aplikasi."
      backTo="/"
    />

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
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import PageHeader from '../components/Header.vue'

const router = useRouter()
const isLoggingOut = ref(false)

const profile = {
  name: 'Okvi Dwi Santra',
  mrn: '000123456',
  nik: '3175xxxxxxxxxxxx',
  phone: '0812-3456-7890'
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
</script>
