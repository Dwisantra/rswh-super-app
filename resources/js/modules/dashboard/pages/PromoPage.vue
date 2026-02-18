<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <header class="bg-gradient-to-r from-teal-600 to-cyan-600 px-4 pb-6 pt-7 text-white">
      <div class="header-top mb-4">        
        <h1 class="text-2xl font-bold">Promo Kesehatan</h1>
      </div>
      <p class="text-sm text-cyan-100">Promo terbaru untuk layanan kesehatan keluarga.</p>
    </header>

    <main class="-mt-3 space-y-4 px-4">
      <PhotoCarousel :slides="promoBanners" :autoplay-ms="4200" />

      <section class="space-y-3">
        <article
          v-for="promo in promos"
          :key="promo.id"
          class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
        >
          <p class="text-lg font-semibold text-slate-900">{{ promo.title }}</p>
          <p class="mt-1 text-sm text-slate-600">{{ promo.subtitle }}</p>
          <button class="mt-3 rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white">{{ promo.cta }}</button>
        </article>
      </section>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import MobileBottomNav from '../components/MobileBottomNav.vue'
import PhotoCarousel from '../components/PhotoCarousel.vue'
import { promoBanners, promos } from '../data/mobileDashboardData'
</script>
