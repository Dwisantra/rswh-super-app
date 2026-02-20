<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24 font-sans">
    <header class="relative overflow-hidden bg-gradient-to-br from-teal-600 to-cyan-700 px-4 pb-12 pt-8 text-white shadow-lg">
      <div class="relative z-10">
        <div class="header-top mb-4">
            <RouterLink to="/" class="back-btn">
                <font-awesome-icon icon="arrow-left" />
            </RouterLink>

            <h1 class="text-xl font-bold tracking-tight">Monitoring Antrian</h1>
        </div>
        <p class="text-sm text-cyan-50 opacity-90">Pantau status panggilan klinik secara real-time.</p>
      </div>
      <div class="absolute -right-6 -top-6 h-32 w-32 rounded-full bg-white/10"></div>
    </header>

    <main class="relative -mt-6 space-y-4 px-4">
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
            <div v-else-if="queues.length === 0" class="py-16 text-center text-slate-500">
            <font-awesome-icon icon="bed" class="mb-3 text-4xl text-slate-300"/>
            <p>Belum ada antrian yang dipanggil.</p>
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
    </main>
    <MobileBottomNav />
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import axios from 'axios'
import MobileBottomNav from '../components/MobileBottomNav.vue'

const loading = ref(false)
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