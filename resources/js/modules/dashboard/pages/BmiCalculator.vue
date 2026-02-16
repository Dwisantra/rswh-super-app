<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-100 pb-24">
    <header class="bg-rose-700 px-4 pb-8 pt-8 text-white">
      <RouterLink to="/" class="text-2xl">←</RouterLink>
      <h1 class="mt-3 text-2xl font-bold">Kalkulator BMI</h1>
      <p class="text-sm text-rose-100">Isi tinggi dan berat badan untuk cek BMI.</p>
    </header>

    <main class="px-4 pt-4">
      <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <label class="mb-1 block text-sm text-slate-600">Tinggi badan (cm)</label>
        <input v-model.number="form.heightCm" type="number" min="1" class="w-full rounded-xl border border-slate-200 px-3 py-2" />

        <label class="mb-1 mt-3 block text-sm text-slate-600">Berat badan (kg)</label>
        <input v-model.number="form.weightKg" type="number" min="1" class="w-full rounded-xl border border-slate-200 px-3 py-2" />

        <button class="mt-4 w-full rounded-xl bg-emerald-600 px-4 py-3 font-semibold text-white" @click="calculateBmi">
          Hitung BMI
        </button>
      </article>

      <article v-if="result" class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
        <p class="text-sm text-emerald-700">Hasil BMI</p>
        <p class="mt-1 text-3xl font-bold text-emerald-800">{{ result.bmi }}</p>
        <p class="text-sm font-medium text-emerald-700">Kategori: {{ result.category }}</p>
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
