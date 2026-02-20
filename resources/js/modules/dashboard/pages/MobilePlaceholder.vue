<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <PageHeader 
      :title="pageTitle"
      description="Halaman placeholder untuk kebutuhan menu mobile." 
    />

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
import PageHeader from '../components/Header.vue'

const route = useRoute()
const pageTitle = computed(() => route.meta.title || 'Menu')
</script>
