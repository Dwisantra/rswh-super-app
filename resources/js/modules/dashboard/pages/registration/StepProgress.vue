<template>
  <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
    <div class="flex items-center gap-2 text-xs">
      <div
        v-for="(label, index) in steps"
        :key="label"
        class="flex flex-1 items-center gap-2"
      >
        <span
          class="flex h-6 w-6 items-center justify-center rounded-full font-semibold"
          :class="index <= currentStep ? 'bg-teal-600 text-white' : 'bg-slate-200 text-slate-500'"
        >
          {{ index + 1 }}
        </span>
        <span :class="index <= currentStep ? 'text-slate-800' : 'text-slate-400'">{{ label }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  currentStep: { type: Number, required: true },
  steps: { type: Array, required: true }
})
</script>