<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <PageHeader
      title="Detail Pengguna"
      description="Informasi profil pasien lebih lengkap."
      backTo="/profil"
    />

    <main class="space-y-3 px-4 pt-4">
      <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm" v-for="item in detailItems" :key="item.label">
        <p class="text-xs text-slate-500">{{ item.label }}</p>
        <p class="text-base font-semibold text-slate-900">{{ item.value }}</p>
      </article>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import PageHeader from '../components/Header.vue'
import { userProfile } from '../data/userProfileData'

const detailItems = computed(() => [
  { label: 'Nama Lengkap', value: userProfile.name },
  { label: 'No. Rekam Medis', value: userProfile.mrn },
  { label: 'NIK', value: userProfile.nik },
  { label: 'No. HP', value: userProfile.phone },
  { label: 'Email', value: userProfile.email },
  { label: 'Jenis Kelamin', value: userProfile.gender },
  { label: 'Golongan Darah', value: userProfile.bloodType },
  { label: 'Tanggal Lahir', value: userProfile.birthDate },
  { label: 'Alamat', value: userProfile.address }
])
</script>