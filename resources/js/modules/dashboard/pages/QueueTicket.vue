<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-100 pb-24">
    <header class="bg-rose-700 px-4 pb-28 pt-8 text-white">
      <RouterLink to="/" class="text-2xl">←</RouterLink>
    </header>

    <section class="-mt-20 px-4">
      <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-lg">
        <h1 class="text-4xl font-bold text-slate-900">Tiket Antrian Anda</h1>
        <p class="mt-2 text-sm text-slate-500">Diharapkan pasien datang 15 menit sebelum jam pelayanan.</p>

        <div class="mt-6 text-center">
          <p class="text-slate-400">Nomor Antrian Loket</p>
          <p class="text-6xl font-bold text-rose-900">{{ ticketData.queueNumber }}</p>
          <p class="mt-3 text-slate-400">Nomor Poliklinik</p>
          <p class="text-5xl font-bold text-rose-900">{{ ticketData.clinicNumber }}</p>
        </div>

        <dl class="mt-6 grid grid-cols-2 gap-y-4 text-sm">
          <div>
            <dt class="text-slate-400">Nama pasien</dt>
            <dd class="font-semibold">{{ ticketData.patientName }}</dd>
          </div>
          <div>
            <dt class="text-slate-400">Poliklinik</dt>
            <dd class="font-semibold">{{ ticketData.clinicName }}</dd>
          </div>
          <div>
            <dt class="text-slate-400">Kode booking</dt>
            <dd class="font-semibold">{{ ticketData.bookingCode }}</dd>
          </div>
          <div>
            <dt class="text-slate-400">Jam Poliklinik</dt>
            <dd class="font-semibold">{{ ticketData.clinicHour }}</dd>
          </div>
          <div>
            <dt class="text-slate-400">Tgl pelayanan</dt>
            <dd class="font-semibold">{{ ticketData.serviceDate }}</dd>
          </div>
          <div>
            <dt class="text-slate-400">Est Pelayanan</dt>
            <dd class="font-semibold">{{ ticketData.estimatedService }}</dd>
          </div>
        </dl>

        <p class="mt-4 text-slate-400">Dokter</p>
        <p class="font-semibold">{{ ticketData.doctorName }}</p>

        <div class="mt-6 flex justify-center">
          <div class="grid h-40 w-40 place-items-center rounded-xl border border-slate-300 bg-white text-center text-xs text-slate-500">
            Dummy QR Code
          </div>
        </div>
      </article>
    </section>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import MobileBottomNav from '../components/MobileBottomNav.vue'
import { ticketData } from '../data/mobileDashboardData'
</script>
