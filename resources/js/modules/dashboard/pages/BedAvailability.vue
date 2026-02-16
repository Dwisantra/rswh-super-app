<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-100 pb-24">
    <header class="bg-rose-700 px-4 pb-8 pt-8 text-white">
      <RouterLink to="/" class="text-2xl">←</RouterLink>
      <h1 class="mt-3 text-2xl font-bold">Ketersediaan Tempat Tidur</h1>
      <p class="text-sm text-rose-100">Data contoh untuk tampilan front-end.</p>
    </header>

    <main class="space-y-3 px-4 pt-4">
      <article
        v-for="item in bedAvailability"
        :key="item.id"
        class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
      >
        <div class="flex items-start justify-between gap-3">
          <div>
            <p class="text-lg font-semibold text-slate-900">{{ item.room }}</p>
            <p class="text-sm text-slate-500">{{ item.class }}</p>
          </div>
          <span
            class="rounded-full px-3 py-1 text-xs font-semibold"
            :class="item.available > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'"
          >
            {{ item.available > 0 ? 'Tersedia' : 'Penuh' }}
          </span>
        </div>

        <div class="mt-3">
          <div class="mb-1 flex items-center justify-between text-xs text-slate-500">
            <span>Ketersediaan</span>
            <span>{{ item.available }}/{{ item.total }}</span>
          </div>
          <div class="h-2 rounded-full bg-slate-100">
            <div
              class="h-2 rounded-full bg-sky-500"
              :style="{ width: `${(item.available / item.total) * 100}%` }"
            />
          </div>
        </div>
      </article>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import MobileBottomNav from '../components/MobileBottomNav.vue'
import { bedAvailability } from '../data/mobileDashboardData'
</script>
