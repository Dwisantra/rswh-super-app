<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-100 pb-24">
    <header class="bg-rose-700 px-4 pb-8 pt-8 text-white">
      <RouterLink to="/" class="text-2xl">←</RouterLink>
      <h1 class="mt-3 text-2xl font-bold">{{ pageTitle }}</h1>
      <p class="text-sm text-rose-100">Halaman placeholder untuk kebutuhan menu mobile.</p>
    </header>

    <main class="px-4 pt-4">
      <article class="rounded-2xl border border-slate-200 bg-white p-4 text-sm text-slate-600 shadow-sm">
        Konten {{ pageTitle.toLowerCase() }} akan diisi pada iterasi berikutnya.
      </article>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import MobileBottomNav from '../components/MobileBottomNav.vue'

const route = useRoute()
const pageTitle = computed(() => route.meta.title || 'Menu')
</script>
