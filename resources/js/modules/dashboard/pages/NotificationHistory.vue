<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <PageHeader
      title="Riwayat Notifikasi"
      description="Semua notifikasi yang dikirim admin/sistem tersimpan di database."
      backTo="/"
    />

    <main class="space-y-3 px-4 pt-4">
      <article v-if="isLoading" class="rounded-2xl border border-slate-200 bg-white p-4 text-sm text-slate-500 shadow-sm">
        Memuat riwayat notifikasi...
      </article>

      <article v-else-if="errorMessage" class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700 shadow-sm">
        {{ errorMessage }}
      </article>

      <article v-else-if="notifications.length === 0" class="rounded-2xl border border-slate-200 bg-white p-4 text-sm text-slate-500 shadow-sm">
        Belum ada notifikasi dari database.
      </article>

      <article
        v-for="item in notifications"
        :key="item.id"
        class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
      >
        <div class="flex items-start justify-between gap-2">
          <p class="text-sm font-semibold text-slate-900">{{ item.title }}</p>
          <span class="rounded-full bg-teal-100 px-2 py-1 text-[10px] font-semibold uppercase text-teal-700">
            {{ item.channel }}
          </span>
        </div>
        <p class="mt-2 text-sm text-slate-600">{{ item.body }}</p>
        <p class="mt-2 text-[11px] text-slate-500">
          Oleh {{ item.sent_by }} · {{ formatDate(item.sent_at) }}
        </p>
      </article>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import PageHeader from '../components/Header.vue'

const notifications = ref([])
const isLoading = ref(true)
const errorMessage = ref('')

const formatDate = (value) => {
  if (!value) return '-'

  return new Date(value).toLocaleString('id-ID', {
    dateStyle: 'medium',
    timeStyle: 'short'
  })
}

onMounted(async () => {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const { data } = await axios.get('/api/v1/notifications/history')
    console.log(data);
    
    notifications.value = data?.data || []
  } catch (_) {
    errorMessage.value = 'Gagal mengambil riwayat notifikasi dari server.'
  } finally {
    isLoading.value = false
  }
})
</script>