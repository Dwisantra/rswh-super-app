<template>
  <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
    <label class="mb-1 block text-sm text-slate-600">Dokter</label>
    <select :value="modelValue" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-base" @change="$emit('update:modelValue', $event.target.value)">
      <option value="">Pilih dokter</option>
      <option v-for="doctor in doctors" :key="doctor.ID" :value="doctor.ID">
        {{ doctor.NM_DOKTER }} ({{ doctor.JAM }})
      </option>
    </select>
    <p v-if="error" class="mt-2 text-xs text-rose-600">{{ error }}</p>
  </article>
</template>

<script setup>
defineProps({
  modelValue: { type: String, default: '' },
  doctors: { type: Array, default: () => [] },
  error: { type: String, default: '' }
})
defineEmits(['update:modelValue'])
</script>