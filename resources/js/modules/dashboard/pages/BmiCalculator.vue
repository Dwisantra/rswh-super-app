<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <header class="bg-gradient-to-r from-teal-600 to-cyan-600 px-4 pb-8 pt-7 text-white">
      <div class="header-top mb-4">
        <RouterLink to="/" class="back-btn">
          <font-awesome-icon icon="arrow-left" />
        </RouterLink>

        <h1 class="header-title">Kalkulator BMI</h1>
      </div>
      <p class="text-sm text-cyan-100">Input dibuat sederhana agar mudah digunakan.</p>
    </header>

    <main class="space-y-4 px-4 pt-4">
      <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <label class="mb-2 block text-base font-medium text-slate-700">Jenis Kelamin</label>
          <div class="flex gap-3 mb-3">
            <button
              class="flex-1 rounded-xl border px-3 py-3 text-base font-semibold"
              :class="form.gender === 'pria'
                ? 'border-transparent text-white bg-gradient-to-r from-sky-500 to-blue-600 shadow-md scale-[1.03]'
                : 'border-slate-200 bg-white text-slate-700'"
              @click="form.gender = 'pria'"
            >            
              <font-awesome-icon icon="mars"/>
            </button>

            <button
              class="flex-1 rounded-xl border px-3 py-3 text-base font-semibold"
              :class="form.gender === 'wanita'
                ? 'border-transparent text-white bg-gradient-to-r from-pink-400 to-rose-500 shadow-md scale-[1.03]'
                : 'border-slate-200 bg-white text-slate-700'"
              @click="form.gender = 'wanita'"
            >
              <font-awesome-icon icon="venus"/>
            </button>
          </div>

        <label class="mb-1 block text-base font-medium text-slate-700">Tinggi badan (cm)</label>
        <input v-model.number="form.heightCm" type="number" min="1" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-lg" />

        <label class="mb-1 mt-3 block text-base font-medium text-slate-700">Berat badan (kg)</label>
        <input v-model.number="form.weightKg" type="number" min="1" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-lg" />

        <button class="mt-4 w-full rounded-xl bg-teal-600 px-4 py-3 text-base font-semibold text-white" @click="calculateBmi">
          Hitung BMI
        </button>
      </article>

      <article 
        v-if="result" 
        class="rounded-2xl border p-4 transition-all duration-300" 
        :class="result.cardColor"
        >
        <p class="text-sm text-teal-700">Hasil BMI</p>
        <p class="mt-1 text-4xl font-bold" :class="result.textColor">
          {{ result.bmi }}
        </p>

        <p class="text-base font-semibold" :class="result.categoryColor">
          Kategori: {{ result.category }}
        </p>
        
        <p class="text-sm text-black">
          {{ result.gender }}
        </p>
        <!-- BMI Meter -->
        <div class="mt-5">
          <div class="mb-2 flex justify-between text-xs font-semibold">
            <span>Kurus</span>
            <span>Normal</span>
            <span>Gemuk</span>
            <span>Obesitas</span>
          </div>
          <div class="relative h-4 w-full rounded-full bg-slate-200 overflow-hidden">
            <div
              class="absolute inset-0 rounded-full"
              style="background: linear-gradient(
                to right,
                #ef4444 0%,
                #22c55e 30%,
                #eab308 55%,
                #f97316 75%,
                #b91c1c 100%
              );">
          </div>
            <div
              class="absolute top-1/2 h-6 w-6 -translate-y-1/2 -translate-x-1/2 rounded-full border-4 border-white bg-white shadow-lg transition-all duration-500"
              :style="{ left: result.progress + '%' }"
            ></div>
          </div>
        </div>
      </article>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import MobileBottomNav from '../components/MobileBottomNav.vue'

const form = reactive({
  gender: 'pria',
  heightCm: null,
  weightKg: null
})
const result = ref(null)

const calculateBmi = () => {
  if (!form.heightCm || !form.weightKg) {
    result.value = null
    return
  }

  const heightM = form.heightCm / 100
  const bmi = form.weightKg / (heightM * heightM)

  let category = ''
  let cardColor = ''
  let textColor = ''
  let gender = form.gender
  let categoryColor = ''
  let progress = ((bmi - 10) / 30) * 100
      progress = Math.max(0, Math.min(progress, 100))

  // Kurus
  if (bmi < 18.5) {
    category = 'Kurus'
    cardColor = 'bg-red-50'
    textColor = 'text-red-600'
    categoryColor = 'text-red-700'
  }

  // Normal
  else if (bmi < 25) {
    category = 'Normal'
    cardColor = 'bg-green-50'
    textColor = 'text-green-600'
    categoryColor = 'text-green-700'
  }

  // Gemuk
  else if (bmi < 30) {
    category = 'Gemuk'
    cardColor = 'bg-yellow-50'
    textColor = 'text-yellow-600'
    categoryColor = 'text-yellow-700'
  }

  // Obesitas
  else if (bmi < 35) {
    category = 'Obesitas I'
    cardColor = 'bg-orange-50'
    textColor = 'text-orange-600'
    categoryColor = 'text-orange-700'
  }

  else if (bmi < 40) {
    category = 'Obesitas II'
    cardColor = 'bg-red-100'
    textColor = 'text-red-700'
    categoryColor = 'text-red-800'
  }

  else {
    category = 'Obesitas III'
    cardColor = 'bg-red-200'
    textColor = 'text-red-800'
    categoryColor = 'text-red-900'
  }

  result.value = {
    bmi: bmi.toFixed(1),
    category,
    cardColor,
    textColor,
    categoryColor,
    progress,
    gender
  }
}
</script>
