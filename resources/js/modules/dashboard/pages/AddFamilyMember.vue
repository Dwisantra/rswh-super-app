<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-10">
    <header class="bg-white px-4 pb-4 pt-6">
      <div class="flex items-center justify-between">
        <RouterLink to="/keluarga" class="back-btn">
          <font-awesome-icon icon="arrow-left" />
        </RouterLink>
        <h1 class="text-2xl font-bold text-slate-900">Tambah Keluarga</h1>
        <span class="w-6" />
      </div>
    </header>

    <main class="space-y-4 px-4 pt-3">
      <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <label class="mb-1 block text-sm text-slate-600">Hubungan dengan pasien</label>
        <select v-model="form.relation" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-base">
          <option value="">Pilih hubungan</option>
          <option v-for="option in relationOptions" :key="option" :value="option">{{ option }}</option>
        </select>

        <label class="mb-1 mt-4 block text-sm text-slate-600">No. Rekam Medis / No. Pasien</label>
        <input v-model.trim="form.mrn" type="text" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-base" placeholder="Contoh: 000123456" />

        <label class="mb-1 mt-4 block text-sm text-slate-600">Tanggal lahir</label>
        <input v-model="form.birthDate" type="date" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-base" />

        <button class="mt-5 w-full rounded-xl bg-teal-600 px-4 py-3 text-base font-semibold text-white" @click="checkPatient">
          Cek Data Pasien
        </button>
      </article>

      <article v-if="checked && !matchedPatient" class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-center">
        <p class="text-2xl">⚠️</p>
        <p class="mt-2 text-xl font-bold text-slate-900">Data tidak ditemukan</p>
        <p class="mt-1 text-sm text-slate-600">Jika belum punya No RM, silakan daftar pasien baru terlebih dahulu.</p>
        <RouterLink
          class="mt-4 inline-block rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white"
          :to="{ path: '/pasien-baru', query: { relation: form.relation, mrn: form.mrn, birthDate: form.birthDate } }"
        >
          Daftar Pasien Baru
        </RouterLink>
      </article>

      <article v-if="matchedPatient" class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
        <p class="text-sm text-emerald-700">Data ditemukan di SIMRS</p>
        <p class="mt-1 text-lg font-bold text-slate-900">{{ matchedPatient.name }}</p>
        <p class="text-sm text-slate-600">No RM: {{ matchedPatient.mrn }} · NIK: {{ matchedPatient.nik }}</p>

        <p v-if="isDuplicate" class="mt-3 rounded-lg bg-amber-100 px-3 py-2 text-sm font-medium text-amber-700">
          Data pasien ini sudah tersimpan di daftar keluarga.
        </p>

        <button
          class="mt-4 w-full rounded-xl px-4 py-3 text-base font-semibold text-white"
          :class="isDuplicate ? 'bg-slate-300' : 'bg-emerald-600'"
          :disabled="isDuplicate"
          @click="saveMember"
        >
          {{ isDuplicate ? 'Sudah tersimpan' : 'Simpan ke Keluarga' }}
        </button>
      </article>
    </main>
  </div>
</template>

<script setup>
import { computed, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { relationOptions, simrsPatients } from '../data/mobileDashboardData'

const router = useRouter()
const form = reactive({ relation: '', mrn: '', birthDate: '' })
const checked = ref(false)
const matchedPatient = ref(null)

const getStoredMembers = () => {
  try {
    return JSON.parse(localStorage.getItem('family_members') || '[]')
  } catch (_) {
    return []
  }
}

const getSimrsPatients = () => {
  try {
    const extra = JSON.parse(localStorage.getItem('simrs_patients_extra') || '[]')
    return [...simrsPatients, ...extra]
  } catch (_) {
    return simrsPatients
  }
}

const isDuplicate = computed(() => {
  if (!matchedPatient.value) return false
  return getStoredMembers().some(
    (item) => item.mrn === matchedPatient.value.mrn && item.birthDate === matchedPatient.value.birthDate
  )
})

const checkPatient = () => {
  checked.value = true
  matchedPatient.value = getSimrsPatients().find((item) => item.mrn === form.mrn && item.birthDate === form.birthDate) || null
}

const saveMember = () => {
  if (!matchedPatient.value || isDuplicate.value || !form.relation) return

  const saved = getStoredMembers()
  saved.push({
    ...matchedPatient.value,
    relation: form.relation
  })

  localStorage.setItem('family_members', JSON.stringify(saved))
  router.push('/keluarga')
}
</script>
