<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <header class="bg-gradient-to-r from-teal-600 to-cyan-600 px-4 pb-8 pt-7 text-white">
      <div class="header-top mb-4">
        <RouterLink to="/" class="back-btn">
          <font-awesome-icon icon="arrow-left" />
        </RouterLink>

        <h1 class="header-title">Pendaftaran</h1>
      </div>

      <p class="text-sm text-cyan-100">Pilih pasien tersimpan agar pendaftaran tidak spam data.</p>
    </header>

    <main class="space-y-3 px-4 pt-4">
      <article v-if="!familyMembers.length" class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
        <p class="text-sm font-semibold text-amber-700">Belum ada data pasien/keluarga tersimpan.</p>
        <p class="mt-1 text-sm text-slate-600">Silakan cek data pasien di SIMRS terlebih dahulu sebelum registrasi.</p>
        <RouterLink to="/keluarga/tambah" class="mt-3 inline-block rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white">Cek Data Pasien</RouterLink>
      </article>

      <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <label class="mb-1 block text-sm text-slate-600">Pasien</label>
        <select v-model="form.selectedMrn" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-base">
          <option value="">Pilih pasien tersimpan</option>
          <option v-for="member in familyMembers" :key="member.mrn" :value="member.mrn">
            {{ member.name }} ({{ member.relation }})
          </option>
        </select>

        <label class="mb-1 mt-3 block text-sm text-slate-600">Klinik</label>
        <select v-model="form.clinic" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-base">
          <option value="">Pilih klinik</option>
          <option v-for="clinic in clinics" :key="clinic" :value="clinic">{{ clinic }}</option>
        </select>

        <label class="mb-1 mt-3 block text-sm text-slate-600">Tanggal kunjungan</label>
        <input v-model="form.visitDate" type="date" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-base" />

        <button
          class="mt-4 w-full rounded-xl px-4 py-3 text-base font-semibold text-white"
          :class="canSubmit ? 'bg-teal-600' : 'bg-slate-300'"
          :disabled="!canSubmit"
          @click="submitRegistration"
        >
          Simpan Registrasi
        </button>
      </article>

      <article v-if="successMessage" class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700">
        {{ successMessage }}
      </article>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import { computed, reactive, ref } from 'vue'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import { doctorSchedules } from '../data/mobileDashboardData'

const form = reactive({ selectedMrn: '', clinic: '', visitDate: '' })
const successMessage = ref('')

const familyMembers = computed(() => {
  try {
    return JSON.parse(localStorage.getItem('family_members') || '[]')
  } catch (_) {
    return []
  }
})

const clinics = [...new Set(doctorSchedules.map((item) => item.clinic))]

const canSubmit = computed(() => form.selectedMrn && form.clinic && form.visitDate)

const submitRegistration = () => {
  if (!canSubmit.value) return
  successMessage.value = 'Registrasi berhasil disimpan menggunakan data pasien yang sudah terverifikasi.'
  form.clinic = ''
  form.visitDate = ''
}
</script>
