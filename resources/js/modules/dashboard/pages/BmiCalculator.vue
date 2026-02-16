<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <header class="bg-gradient-to-r from-teal-600 to-cyan-600 px-4 pb-8 pt-7 text-white">
      <RouterLink to="/" class="text-2xl">←</RouterLink>
      <h1 class="mt-3 text-2xl font-bold">Kalkulator BMI</h1>
      <p class="text-sm text-cyan-100">Input dibuat sederhana agar mudah digunakan.</p>
    </header>

    <main class="space-y-4 px-4 pt-4">
      <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <label class="mb-1 block text-base font-medium text-slate-700">Tinggi badan (cm)</label>
        <input v-model.number="form.heightCm" type="number" min="1" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-lg" />

        <label class="mb-1 mt-3 block text-base font-medium text-slate-700">Berat badan (kg)</label>
        <input v-model.number="form.weightKg" type="number" min="1" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-lg" />

        <button class="mt-4 w-full rounded-xl bg-teal-600 px-4 py-3 text-base font-semibold text-white" @click="calculateBmi">
          Hitung BMI
        </button>
      </article>

      <article v-if="result" class="rounded-2xl border border-teal-200 bg-teal-50 p-4">
        <p class="text-sm text-teal-700">Hasil BMI</p>
        <p class="mt-1 text-4xl font-bold text-teal-800">{{ result.bmi }}</p>
        <p class="text-base font-semibold text-teal-700">Kategori: {{ result.category }}</p>
      </article>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import MobileBottomNav from '../components/MobileBottomNav.vue'

const form = reactive({ heightCm: null, weightKg: null })
const result = ref(null)

const calculateBmi = () => {
  if (!form.heightCm || !form.weightKg) {
    result.value = null
    return
  }

  const heightM = form.heightCm / 100
  const bmi = form.weightKg / (heightM * heightM)
  let category = 'Normal'

  if (bmi < 18.5) category = 'Kurus'
  else if (bmi >= 25 && bmi < 30) category = 'Berlebih'
  else if (bmi >= 30) category = 'Obesitas'

  result.value = {
    bmi: bmi.toFixed(1),
    category
  }
}
</script>
