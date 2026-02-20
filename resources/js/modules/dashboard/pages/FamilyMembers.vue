<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <PageHeader 
      title="Anggota Keluarga"
      backTo="/"
      urlTo="/keluarga/tambah"
    />

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

      <EmptyState
        v-else
        title="Belum Ada Anggota Keluarga"
        description="Tambahkan data keluarga agar bisa daftar berobat lebih cepat"
        actionText="Tambah Keluarga"
        to="/keluarga/tambah"
      />
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import EmptyState from '@/modules/system/pages/EmptyState.vue'
import PageHeader from '../components/Header.vue'

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
