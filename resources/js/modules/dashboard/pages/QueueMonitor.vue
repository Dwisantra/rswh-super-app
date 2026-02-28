<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24 font-sans">
    <PageHeader 
      title="Monitoring Antrian" 
      description="Pantau status panggilan klinik secara real-time."
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
          <div v-if="queues.length === 0" class="py-16 text-center text-slate-500">
            <font-awesome-icon icon="bed" class="mb-3 text-4xl text-slate-300"/>
            <p>Tidak ada antrian tersedia</p>
          </div>

          <template v-else>
            <article
              v-for="(item, index) in queues"
              :key="index"
              class="group relative flex items-center justify-between overflow-hidden rounded-3xl border border-slate-100 bg-white p-5 shadow-md transition-all active:scale-95"
            >
              <div class="absolute left-0 top-0 h-full w-1.5 bg-teal-600"></div>
              <div class="flex-1 pl-2">
                  <div class="mb-1 flex items-center gap-2">
                  <span class="relative flex h-2 w-2">
                      <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                      <span class="relative inline-flex h-2 w-2 rounded-full bg-red-500"></span>
                  </span>
                  <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                      {{ item.namapoli }}
                  </p>
                  </div>

                  <h2 class="animate-pulse-subtle text-4xl font-black tracking-tight text-slate-800">
                  {{ item.antrian }}
                  </h2>
                  
                  <div class="mt-2 flex items-center gap-1 text-[10px] font-bold uppercase text-teal-600">
                  <font-awesome-icon icon="volume-high" class="animate-bounce" />
                  <span>Sedang Dipanggil</span>
                  </div>
              </div>

              <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-teal-50 text-teal-600 transition-colors group-hover:bg-teal-600 group-hover:text-white">
                  <font-awesome-icon icon="user-doctor" size="2xl" />
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
import { onMounted, onUnmounted, ref } from 'vue'
import axios from 'axios'
import PullToRefresh from '../components/PullToRefresh.vue'
import SkeletonLoader from '../components/SkeletonLoader.vue'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import PageHeader from '../components/Header.vue'

const loading = ref(false)
const refreshing = ref(false)
const queues = ref([])
let timer = null

const fetchQueues = async (showLoading = false) => {
  if (showLoading) loading.value = true

  try {
    const response = await axios.get('/api/v1/regonline/queue-monitor')    
    queues.value = response.data.queues?.data ?? []
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}

const onRefresh = async () => {
  try {
    await fetchQueues()
  } finally {
    refreshing.value = false
  }
}

onMounted(() => {
  fetchQueues(true)

  timer = setInterval(() => {
    fetchQueues(false)
  }, 10000)
})

onUnmounted(() => {
  if (timer) clearInterval(timer)
})
</script>