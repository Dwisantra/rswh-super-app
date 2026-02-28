<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <PageHeader 
      title="Anggota Keluarga"
      backTo="/"
      urlTo="/keluarga/tambah"
    />

    <PullToRefresh v-model="refreshing" @refresh="onRefresh">
      <main class="space-y-3 px-4 pt-4">
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
          <div v-else-if="familyMembers.length === 0" class="py-16 text-center text-slate-500">
              <EmptyState
                title="Belum Ada Anggota Keluarga"
                description="Tambahkan data keluarga agar bisa daftar berobat lebih cepat"
                actionText="Tambah Keluarga"
                to="/keluarga/tambah"
              />
          </div>
          
          <template v-else>
            <article v-for="member in familyMembers" :key="member.id" 
              class="rounded-2xl border p-4 shadow-sm transition-all"
              :class="member.is_default ? 'border-teal-500 bg-teal-50 ring-1 ring-teal-500' : 'border-slate-200 bg-white'"
            >
              <div class="flex justify-between items-start">
                <div>
                  <p class="text-lg font-bold text-slate-900 leading-tight">{{ member.name }} ({{ formatGender(member.gender) }})</p>
                  <p class="text-xs font-medium text-slate-500 mt-1 uppercase tracking-wider">
                    <font-awesome-icon icon="user" />
                    {{ member.relation }}
                  </p>
                  <p class="text-xs font-medium text-slate-500 mt-1 uppercase tracking-wider">
                    <font-awesome-icon icon="cake-candles" />
                    {{ formatDate(member.birth_date) }}
                  </p>
                  <p class="text-xs font-medium text-slate-500 mt-1 uppercase tracking-wider">
                    <font-awesome-icon icon="place-of-worship" />
                    {{ member.address }}
                  </p>
                  <p class="text-xs font-medium text-slate-500 mt-1 uppercase tracking-wider">
                    <font-awesome-icon icon="credit-card" />
                    {{ member.kap || '-' }}
                  </p>
                </div>
                <span v-if="member.is_default" class="bg-teal-600 text-white text-[10px] px-2.5 py-1 rounded-full font-bold">
                  UTAMA
                </span>
              </div>

              <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                <div class="text-xs text-slate-500">
                  RM: <span class="font-bold text-slate-700">{{ member.norm }}</span>
                </div>
                <div class="text-xs text-slate-500">
                  NIK: <span class="font-bold text-slate-700">{{ member.nik }}</span>
                </div>
                
                <button 
                  v-if="!member.is_default" 
                  @click="handleSetDefault(member.id)"
                  class="text-xs font-bold text-teal-600 hover:text-teal-700 active:scale-95 transition-transform"
                >
                  Jadikan Utama
                </button>
                <span v-else class="text-[10px] italic text-teal-600 font-medium">Pasien Terpilih</span>
              </div>
            </article>
          </template>

        <!-- <EmptyState
          v-else
          title="Belum Ada Anggota Keluarga"
          description="Tambahkan data keluarga agar bisa daftar berobat lebih cepat"
          actionText="Tambah Keluarga"
          to="/keluarga/tambah"
        /> -->
      </main>
    </PullToRefresh>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import PullToRefresh from '../components/PullToRefresh.vue'
import SkeletonLoader from '../components/SkeletonLoader.vue'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import EmptyState from '@/modules/system/pages/EmptyState.vue'
import PageHeader from '../components/Header.vue'

const familyMembers = ref([])
const loading = ref(true)
const refreshing = ref(false)

const onRefresh = async () => {
  try {
    await fetchPatients()
  } finally {
    refreshing.value = false
  }
}

const fetchPatients = async () => {
  loading.value = true
  try {
    // Sesuaikan prefix API jika menggunakan Laravel Modular (misal: /api/v1/patient/family)
    const res = await axios.get('/api/v1/patients/family')
    familyMembers.value = res.data
    
  } catch (err) {
    console.error('Gagal mengambil data:', err)
  } finally {
    loading.value = false
  }
}

const handleSetDefault = async (id) => {
  try {
    await axios.post(`/api/v1/patients/family/${id}/set-default`)
    await fetchPatients() 
  } catch (err) {
    console.error('Gagal update default:', err)
  }
}

onMounted(fetchPatients)

const formatDate = (value) =>
  new Date(value).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })

const formatGender = (value) => {  
  if (!value) return '';
  const g = value.toString().toUpperCase();
  if (g == 'LAKI-LAKI') return 'L';
  if (g == 'PEREMPUAN') return 'P';
  
  return value;
};
</script>
