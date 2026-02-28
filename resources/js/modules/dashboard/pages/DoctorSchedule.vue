<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24 font-sans">
    <PageHeader 
      title="Jadwal Dokter" 
      description="Temukan jadwal dokter pada halaman ini."
      backTo="/"
    />

    <PullToRefresh v-model="refreshing" @refresh="onRefresh">
      <main class="space-y-3 px-4 pt-4">
        <section class="sticky top-0 z-10 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
          <p class="mb-2 text-sm font-medium text-slate-700">Filter Jadwal</p>

          <div class="grid grid-cols-1 gap-2">
            <!-- filter by nama -->
            <div class="search-box">
              <font-awesome-icon icon="magnifying-glass" class="search-icon" />
              <input
                v-model.trim="searchName"
                type="text"
                placeholder="Cari nama dokter..."
                class="search-input"
              />
            </div>          

            <!-- filter by tanggal -->
            <!-- <input 
              v-model="selectedDate" 
              type="date" 
              class="rounded-xl border border-slate-200 px-3 py-2 text-sm" 
            /> -->

            <!-- filter by poli -->
            <select v-model="selectedClinic" class="rounded-xl border border-slate-200 px-3 py-2 text-sm">
              <option value="">Semua Poliklinik</option>
              <option v-for="clinic in uniqueClinics" :key="clinic" :value="clinic">{{ clinic }}</option>
            </select>
          </div>
        </section>

        <!-- Skeleton Loader -->
        <SkeletonLoader 
          :loading="loading && !refreshing" 
          type="card" 
          :count="4" 
        />

        <template v-if="!loading || refreshing">
          <div v-if="filteredSchedules.length === 0" class="py-16 text-center text-slate-500">
            <font-awesome-icon icon="bed" class="mb-3 text-4xl text-slate-300"/>
            <p>Tidak ada jadwal dokter tersedia</p>
          </div>
          
          <template v-else>
            <article
              v-for="doc in paginatedSchedules"
              :key="doc.id"
              class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md"
            >
              <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-teal-100 text-teal-600">
                  <font-awesome-icon icon="user-doctor" class="text-lg" />
                </div>

                <!-- nama & poli -->
                <div class="flex-1">
                  <p class="text-base font-semibold text-slate-900 leading-tight">
                    {{ doc.doctor }}
                  </p>
                  <div class="mt-1">
                    <span class="inline-block rounded-full bg-cyan-50 px-2.5 py-1 text-xs font-medium text-cyan-700">
                      <font-awesome-icon icon="stethoscope"/>
                      {{ doc.clinic }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- jadwal praktek -->
              <div class="mt-4 border-t pt-3 space-y-2">
                <div
                  v-for="(sch, i) in doc.schedules"
                  :key="i"
                  class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2"
                >
                  <span class="text-sm font-medium text-slate-700">
                    {{ sch.day }}
                  </span>
                  <div class="flex items-center gap-2 text-sm text-slate-600">
                    <font-awesome-icon icon="clock" class="text-teal-500" />
                    <span>{{ sch.time }}</span>
                  </div>
                </div>
              </div>
            </article>
          </template>
        </template>
        <Pagination
          v-model="currentPage"
          :total-items="filteredSchedules.length"
          :items-per-page="perPage"
        />
      </main>
    </PullToRefresh>
    
    <MobileBottomNav />
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import PullToRefresh from '../components/PullToRefresh.vue'
import SkeletonLoader from '../components/SkeletonLoader.vue'
import axios from 'axios'
import Pagination from '../components/Pagination.vue'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import PageHeader from '../components/Header.vue'

const schedules = ref([])
const refreshing = ref(false)
const selectedDate = ref('')
const selectedClinic = ref('')
const searchName = ref('')
const loading = ref(false)

const currentPage = ref(1)
const perPage = 8

const formatDoctor = (name) => {
  return name?.toLowerCase()
}

const formatTime = (start, end) => {
  return start.substring(0,5) + ' - ' + end.substring(0,5)
}

const uniqueClinics = computed(() => {
  return [...new Set(schedules.value.map(d => d.clinic))]
})

const getDayName = (dateStr) => {
  if (!dateStr) return null
  const days = [
    'MINGGU','SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU'
  ]
  const date = new Date(dateStr)
  return days[date.getDay()]
}

const onRefresh = async () => {
  try {
    await getSchedules()
  } finally {
    refreshing.value = false
  }
}

const getSchedules = async () => {
  loading.value = true

  try {
    const res = await axios.get('/api/v1/regonline/doctor-schedules')
    const raw = res.data.data
    const grouped = {}

    raw.forEach(item => {
      const kd = item.KD_DOKTER
      const hari = item.NM_HARI
      const jam = formatTime(item.JAM_MULAI, item.JAM_SELESAI)

      if (!grouped[kd]) {
        grouped[kd] = {
          id: kd,
          doctor: formatDoctor(item.NM_DOKTER),
          clinic: item.REFERENSI?.POLI?.NMPOLI ?? '-',
          schedules: {}
        }
      }
      if (!grouped[kd].schedules[hari]) {
        grouped[kd].schedules[hari] = []
      }
      grouped[kd].schedules[hari].push(jam)
    })
    schedules.value = Object.values(grouped).map(doc => ({
      ...doc,
      schedules: Object.entries(doc.schedules).map(([day, times]) => ({
        day,
        time: times.join(', ')
      }))
    }))

  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
}

const filteredSchedules = computed(() => {
  let data = schedules.value

  if (selectedClinic.value) {
    data = data.filter(d => d.clinic === selectedClinic.value)
  }

  if (searchName.value) {
    const keyword = searchName.value.toLowerCase()
    data = data.filter(d =>
      d.doctor.toLowerCase().includes(keyword)
    )
  }

  if (selectedDate.value) {
    const selectedDay = getDayName(selectedDate.value)

    data = data
      .map(doc => {
        const matched = doc.schedules.filter(
          s => s.day === selectedDay
        )

        if (matched.length === 0) return null

        return {
          ...doc,
          schedules: matched
        }
      })
      .filter(Boolean)
  }

  return data
})

const paginatedSchedules = computed(() => {
  const start = (currentPage.value - 1) * perPage
  const end = start + perPage
  return filteredSchedules.value.slice(start, end)
})

watch(searchName, () => {
  currentPage.value = 1
})

watch(selectedClinic, () => {
  currentPage.value = 1
})

onMounted(() => {
  getSchedules()
})
</script>
