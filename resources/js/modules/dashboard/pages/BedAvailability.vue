<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24 font-sans">
    <PageHeader 
      title="Ketersediaan Tempat Tidur" 
      description="Pantau ketersediaan tempat tidur pada halaman ini."
      backTo="/" 
    />

    <PullToRefresh v-model="refreshing" @refresh="onRefresh">
      <main class="space-y-3 px-4 pt-4">
        <!-- Skeleton Loader -->
        <SkeletonLoader 
          :loading="loading && !refreshing" 
          type="card" 
          :count="4" 
        />

        <template v-if="!loading || refreshing">
          <div v-if="bedAvailability.length === 0" class="py-16 text-center text-slate-500">
            <font-awesome-icon icon="bed" class="mb-3 text-4xl text-slate-300"/>
            <p>Tidak ada ketersediaan tempat tidur tersedia</p>
          </div>

          <template v-else>
            <article
              v-for="item in bedAvailability"
              :key="item.room"
              class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md"
            >
              <div class="flex items-start justify-between gap-3">
                <div class="flex gap-3">
                  <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                    <font-awesome-icon icon="bed" class="text-lg"/>
                  </div>

                  <div>
                    <p class="text-lg font-semibold text-slate-900">{{ item.room }}</p>
                    <!-- <p class="text-sm text-slate-500">{{ item.class }}</p>               -->
                  </div>
                </div>
              </div>

              <!-- Statistik Bed -->
              <div class="mt-4 grid grid-cols-3 gap-3">

                <!-- Kosong -->
                <div class="rounded-xl bg-emerald-50 p-3 text-center">
                  <div class="mb-1 text-emerald-600">
                    <font-awesome-icon icon="bed"/>
                  </div>
                  <p class="text-xs text-slate-500">Kosong</p>
                  <p class="text-lg font-bold text-emerald-600">
                    {{ item.available }}
                  </p>
                </div>

                <!-- Terisi -->
                <div class="rounded-xl bg-rose-50 p-3 text-center">
                  <div class="mb-1 text-rose-600">
                    <font-awesome-icon icon="user-injured"/>
                  </div>
                  <p class="text-xs text-slate-500">Terisi</p>
                  <p class="text-lg font-bold text-rose-600">
                    {{ item.occupied }}
                  </p>
                </div>

                <!-- Kapasitas -->
                <div class="rounded-xl bg-sky-50 p-3 text-center">
                  <div class="mb-1 text-sky-600">
                    <font-awesome-icon icon="hospital-user"/>
                  </div>
                  <p class="text-xs text-slate-500">Kapasitas</p>
                  <p class="text-lg font-bold text-sky-600">
                    {{ item.total }}
                  </p>
                </div>

              </div>
            </article>
          </template>
        </template>
      </main>
    </PullToRefresh>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import PullToRefresh from '../components/PullToRefresh.vue'
import SkeletonLoader from '../components/SkeletonLoader.vue'
import axios from 'axios'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import PageHeader from '../components/Header.vue'

const bedAvailability = ref([])
const loading = ref(true)
const refreshing = ref(false)

const fetchBeds = async () => {
  try {
    loading.value = true

    const res = await axios.get('/api/v1/regonline/bed-capacity')
    const raw = res.data.data

    bedAvailability.value = raw.map(item => ({
      room: item.kamar,
      // class: 'Rawat Inap',
      available: item.kosong,
      occupied: item.terisi,
      total: item.total
    }))
  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
}

const onRefresh = async () => {
  try {
    await fetchBeds()
  } finally {
    refreshing.value = false
  }
}

onMounted(() => {
  fetchBeds()
})
</script>

