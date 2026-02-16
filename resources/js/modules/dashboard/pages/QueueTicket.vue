<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <header class="bg-gradient-to-r from-teal-600 to-cyan-600 px-4 pb-20 pt-7 text-white">
      <RouterLink to="/" class="text-2xl">←</RouterLink>
      <h1 class="mt-3 text-2xl font-bold">Tiket Antrian</h1>
      <p class="text-sm text-cyan-100">Silakan datang 15 menit sebelum jadwal.</p>
    </header>

    <main class="-mt-14 px-4">
      <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-md">
        <div class="text-center">
          <p class="text-sm text-slate-500">Nomor Antrian Loket</p>
          <p class="mt-1 text-6xl font-extrabold tracking-wide text-teal-700">{{ ticketData.queueNumber }}</p>
          <p class="mt-2 text-sm text-slate-500">Nomor Poliklinik</p>
          <p class="text-4xl font-bold text-slate-900">{{ ticketData.clinicNumber }}</p>
        </div>

        <dl class="mt-6 grid grid-cols-2 gap-4 text-sm">
          <div>
            <dt class="text-slate-500">Nama pasien</dt>
            <dd class="font-semibold text-slate-900">{{ ticketData.patientName }}</dd>
          </div>
          <div>
            <dt class="text-slate-500">Poliklinik</dt>
            <dd class="font-semibold text-slate-900">{{ ticketData.clinicName }}</dd>
          </div>
          <div>
            <dt class="text-slate-500">Kode booking</dt>
            <dd class="font-semibold text-slate-900">{{ ticketData.bookingCode }}</dd>
          </div>
          <div>
            <dt class="text-slate-500">Jam poliklinik</dt>
            <dd class="font-semibold text-slate-900">{{ ticketData.clinicHour }}</dd>
          </div>
          <div>
            <dt class="text-slate-500">Tanggal pelayanan</dt>
            <dd class="font-semibold text-slate-900">{{ ticketData.serviceDate }}</dd>
          </div>
          <div>
            <dt class="text-slate-500">Estimasi pelayanan</dt>
            <dd class="font-semibold text-slate-900">{{ ticketData.estimatedService }}</dd>
          </div>
        </dl>

        <div class="mt-4 rounded-xl bg-slate-50 p-3">
          <p class="text-xs text-slate-500">Dokter</p>
          <p class="font-semibold text-slate-900">{{ ticketData.doctorName }}</p>
        </div>

        <div class="mt-5 flex justify-center">
          <div class="grid h-40 w-40 place-items-center rounded-2xl border border-slate-300 bg-white text-center text-xs text-slate-500">
            Dummy QR Code
          </div>
        </div>
      </article>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import MobileBottomNav from '../components/MobileBottomNav.vue'
import { ticketData } from '../data/mobileDashboardData'
</script>
