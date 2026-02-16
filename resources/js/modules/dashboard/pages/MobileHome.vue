<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-100 pb-24">
    <section class="rounded-b-3xl bg-rose-700 px-4 pb-5 pt-6 text-white">
      <div class="flex items-center gap-3">
        <div class="flex-1 rounded-full bg-white px-4 py-3 text-slate-500">🔍 Cari layanan rumah sakit</div>
        <div class="grid h-12 w-12 place-items-center rounded-full bg-white text-xl text-emerald-600">👤</div>
      </div>

      <article class="mt-4 rounded-2xl bg-rose-600 p-4">
        <p class="inline-block rounded-full bg-white px-3 py-1 text-xs font-semibold text-emerald-700">Promo Kesehatan</p>
        <h1 class="mt-3 text-3xl font-black leading-tight">Diskon Medical Check Up</h1>
        <p class="mt-1 text-sm text-rose-100">Gunakan voucher untuk pemeriksaan kesehatan minggu ini.</p>
      </article>
    </section>

    <section class="-mt-4 space-y-4 px-4">
      <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-slate-500">No. RM</p>
            <p class="text-xl font-bold text-slate-800">000123456</p>
          </div>
          <div class="text-right">
            <p class="text-sm text-slate-500">Status</p>
            <p class="font-semibold text-emerald-600">Aktif</p>
          </div>
        </div>
      </article>

      <ServiceMenuGrid :menus="serviceMenus" />

      <div class="space-y-3">
        <h2 class="text-2xl font-bold text-slate-900">Promo yang wajib dicek</h2>
        <article v-for="promo in promos" :key="promo.id" class="rounded-2xl bg-emerald-700 p-4 text-white">
          <p class="text-lg font-semibold">{{ promo.title }}</p>
          <p class="mt-1 text-sm text-emerald-100">{{ promo.subtitle }}</p>
          <button class="mt-3 rounded-lg bg-white px-3 py-1 text-sm font-semibold text-emerald-700">{{ promo.cta }}</button>
        </article>
      </div>
    </section>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import MobileBottomNav from '../components/MobileBottomNav.vue'
import ServiceMenuGrid from '../components/ServiceMenuGrid.vue'
import { promos, serviceMenus } from '../data/mobileDashboardData'
</script>
