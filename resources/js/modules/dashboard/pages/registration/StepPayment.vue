<template>
  <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
    <label class="mb-1 block text-sm text-slate-600">Cara bayar</label>
    <select :value="modelValue" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-base" @change="$emit('update:modelValue', $event.target.value)">
      <option value="">Pilih cara bayar</option>
      <option v-for="method in methods" :key="method.ID || method.KODE || method.kode" :value="method.ID || method.KODE || method.kode">
        {{ method.DESKRIPSI || method.NAMA || method.nama || method.KETERANGAN || 'Cara Bayar' }}
      </option>
    </select>
    <p v-if="error" class="mt-2 text-xs text-rose-600">{{ error }}</p>
  </article>
</template>

<script setup>
defineProps({
  modelValue: { type: String, default: '' },
  methods: { type: Array, default: () => [] },
  error: { type: String, default: '' }
})
defineEmits(['update:modelValue'])
</script>