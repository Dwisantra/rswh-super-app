<template>
  <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
    <label class="mb-1 block text-sm text-slate-600">Tanggal kunjungan</label>
    <input 
      :value="modelValue" 
      type="date" 
      :min="min" 
      :max="max"
      class="w-full rounded-xl border border-slate-200 px-3 py-3 text-base" 
      @input="$emit('update:modelValue', $event.target.value)" 
    />
    <p v-if="error" class="mt-2 text-xs text-rose-600">{{ error }}</p>
  </article>
</template>

<script setup>
defineProps({
  modelValue: { type: String, default: '' },
  error: { type: String, default: '' },
  min: { type: String, default: '' }, // Menerima batas minimal
  max: { type: String, default: '' }  // Menerima batas maksimal
})
defineEmits(['update:modelValue'])
</script>