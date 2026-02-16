<template>
  <div class="min-h-screen bg-slate-50 p-4 pb-20 md:p-8">
    <div class="mx-auto max-w-5xl space-y-5">
      <header class="rounded-2xl bg-white p-5 shadow-sm border border-slate-200">
        <div class="flex items-center justify-between gap-2">
          <div>
            <p class="text-xs uppercase tracking-wide text-slate-500">Portal Pasien</p>
            <h1 class="text-xl font-semibold text-slate-800">Halo, {{ profile.name || '-' }}</h1>
            <p class="text-sm text-slate-500">NIK: {{ profile.nik || '-' }} · No RM: {{ profile.norm || '-' }}</p>
          </div>
          <button class="rounded-lg border border-slate-200 px-3 py-2 text-sm" @click="logout">Logout</button>
        </div>
      </header>

      <section class="grid gap-3 md:grid-cols-3">
        <article class="rounded-xl bg-white p-4 border border-slate-200">
          <p class="text-sm text-slate-500">Pendaftaran Saya</p>
          <p class="mt-2 text-2xl font-semibold text-slate-800">{{ summary.registration_count ?? 0 }}</p>
        </article>
        <article class="rounded-xl bg-white p-4 border border-slate-200">
          <p class="text-sm text-slate-500">Jadwal Dokter</p>
          <p class="mt-2 text-2xl font-semibold text-slate-800">{{ summary.doctor_schedule_count ?? 0 }}</p>
        </article>
        <article class="rounded-xl bg-white p-4 border border-slate-200">
          <p class="text-sm text-slate-500">Ruang Rawat</p>
          <p class="mt-2 text-2xl font-semibold text-slate-800">{{ summary.bed_room_count ?? 0 }}</p>
        </article>
      </section>

      <section class="grid gap-5 lg:grid-cols-2">
        <article class="rounded-2xl bg-white p-5 border border-slate-200 shadow-sm">
          <h2 class="font-semibold text-slate-800">Pendaftaran Online</h2>
          <form class="mt-3 space-y-3" @submit.prevent="submitRegistration">
            <input v-model="registrationForm.clinic_name" class="w-full rounded-lg border border-slate-200 px-3 py-2" placeholder="Nama Poli" />
            <input v-model="registrationForm.doctor_name" class="w-full rounded-lg border border-slate-200 px-3 py-2" placeholder="Nama Dokter" />
            <input v-model="registrationForm.schedule_at" type="datetime-local" class="w-full rounded-lg border border-slate-200 px-3 py-2" />
            <input v-model="registrationForm.complaint" class="w-full rounded-lg border border-slate-200 px-3 py-2" placeholder="Keluhan" />
            <button class="rounded-lg bg-sky-600 px-4 py-2 text-white">Daftarkan</button>
          </form>
        </article>

        <article class="rounded-2xl bg-white p-5 border border-slate-200 shadow-sm">
          <h2 class="font-semibold text-slate-800">Kalkulator BMI</h2>
          <div class="mt-3 grid grid-cols-2 gap-3">
            <input v-model.number="bmiForm.height_cm" type="number" class="rounded-lg border border-slate-200 px-3 py-2" placeholder="Tinggi (cm)" />
            <input v-model.number="bmiForm.weight_kg" type="number" class="rounded-lg border border-slate-200 px-3 py-2" placeholder="Berat (kg)" />
          </div>
          <button class="mt-3 rounded-lg bg-emerald-600 px-4 py-2 text-white" @click="calculateBmi">Hitung BMI</button>
          <p v-if="bmiResult" class="mt-3 rounded-lg bg-emerald-50 px-3 py-2 text-sm text-emerald-700">BMI: {{ bmiResult.bmi }} ({{ bmiResult.category }})</p>
        </article>
      </section>

      <section class="grid gap-5 lg:grid-cols-2">
        <article class="rounded-2xl bg-white p-5 border border-slate-200 shadow-sm">
          <h2 class="font-semibold text-slate-800">Jadwal Dokter</h2>
          <ul class="mt-3 space-y-2 text-sm">
            <li v-for="(item, idx) in doctorSchedules" :key="idx" class="rounded-lg border border-slate-100 p-3">
              <p class="font-medium">{{ item.doctor_name }}</p>
              <p class="text-slate-500">{{ item.clinic_name }} · {{ item.schedule }}</p>
            </li>
          </ul>
        </article>

        <article class="rounded-2xl bg-white p-5 border border-slate-200 shadow-sm">
          <h2 class="font-semibold text-slate-800">Kapasitas Tempat Tidur</h2>
          <ul class="mt-3 space-y-2 text-sm">
            <li v-for="(item, idx) in bedCapacity" :key="idx" class="rounded-lg border border-slate-100 p-3 flex items-center justify-between">
              <span>{{ item.room }}</span>
              <span class="font-medium">{{ item.available }}/{{ item.total }} tersedia</span>
            </li>
          </ul>
        </article>
      </section>

      <section class="rounded-2xl bg-white p-5 border border-slate-200 shadow-sm">
        <div class="mb-3 flex items-center justify-between">
          <h2 class="font-semibold text-slate-800">Riwayat Pendaftaran</h2>
          <label class="text-sm text-slate-600 flex items-center gap-2">
            <input type="checkbox" v-model="includeFamily" @change="loadRegistrations" />
            Tampilkan keluarga
          </label>
        </div>
        <ul class="space-y-2 text-sm">
          <li v-for="item in registrations" :key="item.id" class="rounded-lg border border-slate-100 p-3">
            <p class="font-medium">{{ item.clinic_name }} - {{ item.doctor_name }}</p>
            <p class="text-slate-500">{{ formatDate(item.schedule_at) }} · Antrian {{ item.queue_number || '-' }}</p>
          </li>
          <li v-if="registrations.length === 0" class="text-slate-500">Belum ada pendaftaran.</li>
        </ul>
      </section>
    </div>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const summary = reactive({})
const profile = reactive({})
const doctorSchedules = ref([])
const bedCapacity = ref([])
const registrations = ref([])
const includeFamily = ref(false)
const bmiResult = ref(null)

const registrationForm = reactive({ clinic_name: '', doctor_name: '', schedule_at: '', complaint: '' })
const bmiForm = reactive({ height_cm: null, weight_kg: null })

const loadDashboard = async () => {
  const { data } = await axios.get('/api/v1/regonline/dashboard')
  Object.assign(summary, data)
  Object.assign(profile, data.user || {})
}

const loadReferences = async () => {
  const [doctorRes, bedRes] = await Promise.all([
    axios.get('/api/v1/regonline/doctor-schedules'),
    axios.get('/api/v1/regonline/bed-capacity')
  ])
  doctorSchedules.value = doctorRes.data
  bedCapacity.value = bedRes.data
}

const loadRegistrations = async () => {
  const { data } = await axios.get('/api/v1/regonline/registrations', {
    params: { family: includeFamily.value ? 1 : 0 }
  })
  registrations.value = data
}

const submitRegistration = async () => {
  await axios.post('/api/v1/regonline/registrations', registrationForm)
  Object.assign(registrationForm, { clinic_name: '', doctor_name: '', schedule_at: '', complaint: '' })
  await loadDashboard()
  await loadRegistrations()
}

const calculateBmi = async () => {
  const { data } = await axios.post('/api/v1/regonline/bmi', bmiForm)
  bmiResult.value = data
}

const logout = async () => {
  try {
    await axios.post('/api/v1/logout')
  } catch (_) {
    // ignore
  }
  localStorage.removeItem('auth_token')
  delete axios.defaults.headers.common.Authorization
  router.push('/login')
}

const formatDate = (val) => new Date(val).toLocaleString('id-ID')

onMounted(async () => {
  await loadDashboard()
  await Promise.all([loadReferences(), loadRegistrations()])
})
</script>
