<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <header class="bg-gradient-to-r from-teal-600 to-cyan-600 px-4 pb-8 pt-7 text-white">
      <div class="header-top mb-4">
        <!-- <RouterLink to="/" class="back-btn">
          <font-awesome-icon icon="arrow-left" />
        </RouterLink> -->

        <h1 class="text-2xl font-bold">{{ pageTitle }}</h1>
      </div>      
      <p class="text-sm text-cyan-100">Halaman placeholder untuk kebutuhan menu mobile.</p>
    </header>

    <main class="px-4 pt-4">
      <article class="rounded-2xl border border-slate-200 bg-white p-4 text-sm text-slate-600 shadow-sm">
        <DevState />
        <!-- Konten {{ pageTitle.toLowerCase() }} akan diisi pada iterasi berikutnya. -->
      </article>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import DevState from '@/modules/system/pages/UnderConstruction.vue'

const route = useRoute()
const pageTitle = computed(() => route.meta.title || 'Menu')
</script>
