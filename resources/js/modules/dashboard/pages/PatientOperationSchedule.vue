<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24 font-sans">
    <header class="relative overflow-hidden bg-gradient-to-br from-teal-600 to-cyan-700 px-4 pb-12 pt-8 text-white shadow-lg">
      <div class="relative z-10">
        <div class="header-top mb-4">
          <RouterLink to="/" class="back-btn">
            <font-awesome-icon icon="arrow-left" />
          </RouterLink>

          <h1 class="header-title">Jadwal Operasi Pasien</h1>
        </div>
        <p class="text-sm text-cyan-100">Pilih pasien untuk melihat jadwal operasi.</p>
      </div>
      <div class="absolute -right-6 -top-6 h-32 w-32 rounded-full bg-white/10"></div>
    </header>

    <main class="space-y-3 px-4 pt-4">
      <section class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
        <p class="mb-2 text-sm font-medium text-slate-700">Pilih Pasien</p>
        <select v-model="selectedPatientId" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" @change="fetchOperations">
          <option v-for="patient in patients" :key="patient.id" :value="patient.id">
            {{ patient.name }} ({{ patient.norm || patient.nik || '-' }})
          </option>
        </select>
      </section>

      <!-- Skeleton Loading -->
        <div v-if="loading" class="space-y-3">
            <div
                v-for="n in 4"
                :key="n"
                class="animate-pulse rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
            >
                <div class="flex items-start justify-between gap-3">
                <div class="space-y-2 w-full">
                    <div class="h-5 w-40 rounded bg-slate-200"></div>
                    <div class="h-4 w-24 rounded bg-slate-200"></div>
                    <div class="h-4 w-28 rounded bg-slate-200"></div>
                </div>
                    <div class="h-6 w-20 rounded-full bg-slate-200"></div>
                </div>

                <div class="mt-4 space-y-2">
                <div class="h-3 w-full rounded bg-slate-200"></div>
                <div class="h-2.5 w-full rounded-full bg-slate-200"></div>
                </div>
            </div>
            </div>

            <!-- Tidak Ada Data -->
            <div v-else-if="operations.length === 0" class="py-16 text-center text-slate-500">
            <font-awesome-icon icon="bed" class="mb-3 text-4xl text-slate-300"/>
            <p>Tidak ada jadwal operasi untuk pasien terpilih.</p>
        </div>
        
        <template v-else>
          <article
            v-for="(item, index) in operations"
            :key="`${item.kodebooking || item.nmtindakan || index}`"
            class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
          >
            <p class="text-base font-semibold text-slate-900">{{ item.nmtindakan || item.operation_name || 'Tindakan Operasi' }}</p>
            <p class="mt-1 text-sm text-slate-600">{{ item.tgloperasi || item.operation_date || '-' }}</p>
            <p class="mt-2 inline-block rounded-full bg-cyan-50 px-2.5 py-1 text-xs font-medium text-cyan-700">
              {{ item.status || 'Terjadwal' }}
            </p>
          </article>
        </template>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import MobileBottomNav from '../components/MobileBottomNav.vue'

const loading = ref(false)
const patients = ref([])
const operations = ref([])
const selectedPatientId = ref(null)

const fetchOperations = async () => {
  loading.value = true

  try {
    const params = selectedPatientId.value ? { patient_id: selectedPatientId.value } : {}
    const response = await axios.get('/api/v1/regonline/operation-schedules/patient', { params })

    patients.value = response.data?.patients ?? []
    selectedPatientId.value = response.data?.selected_patient_id ?? selectedPatientId.value
    operations.value = response.data?.operations ?? []
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchOperations()
})
</script>