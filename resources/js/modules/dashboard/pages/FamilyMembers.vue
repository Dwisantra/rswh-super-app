<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <header class="flex items-center justify-between bg-white px-4 pb-4 pt-6">
      <RouterLink to="/" class="text-2xl">←</RouterLink>
      <h1 class="text-2xl font-bold text-slate-900">Anggota Keluarga</h1>
      <RouterLink to="/keluarga/tambah" class="text-3xl leading-none">+</RouterLink>
    </header>

    <main class="px-4 pt-3">
      <section v-if="familyMembers.length" class="space-y-3">
        <article
          v-for="member in familyMembers"
          :key="`${member.mrn}-${member.birthDate}`"
          class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
        >
          <div class="flex items-start justify-between gap-2">
            <div>
              <p class="text-lg font-semibold text-slate-900">{{ member.name }}</p>
              <p class="text-sm text-slate-500">{{ member.relation }}</p>
            </div>
            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Tersimpan</span>
          </div>
          <dl class="mt-3 grid grid-cols-2 gap-2 text-sm">
            <div>
              <dt class="text-slate-500">No RM</dt>
              <dd class="font-semibold">{{ member.mrn }}</dd>
            </div>
            <div>
              <dt class="text-slate-500">Tgl Lahir</dt>
              <dd class="font-semibold">{{ formatDate(member.birthDate) }}</dd>
            </div>
          </dl>
        </article>
      </section>

      <section v-else class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">
        <p class="text-2xl">🔎</p>
        <p class="mt-3 text-2xl font-bold text-slate-800">Kosong</p>
        <p class="mt-1 text-base">Anda belum memiliki data keluarga</p>
        <RouterLink to="/keluarga/tambah" class="mt-5 inline-block rounded-xl bg-teal-600 px-4 py-2 font-semibold text-white">Tambah Keluarga</RouterLink>
      </section>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import MobileBottomNav from '../components/MobileBottomNav.vue'

const familyMembers = computed(() => {
  try {
    return JSON.parse(localStorage.getItem('family_members') || '[]')
  } catch (_) {
    return []
  }
})

const formatDate = (value) =>
  new Date(value).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })
</script>
