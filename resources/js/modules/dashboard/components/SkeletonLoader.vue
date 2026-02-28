<template>
  <div v-if="loading" class="skeleton-wrapper space-y-4">
    <template v-if="type === 'card'">
        <div v-for="n in count" :key="n" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm animate-pulse mb-3">
            <div class="flex items-start justify-between gap-3">
            <div class="space-y-2 w-full">
                <div class="h-5 w-40 rounded bg-slate-200"></div>
                <div class="h-4 w-24 rounded bg-slate-200"></div>
                <div class="h-4 w-28 rounded bg-slate-200"></div>
            </div>
            <div class="h-6 w-20 rounded-full bg-slate-200"></div>
            </div>
            <div class="mt-4 space-y-2">
            <div class="h-3 w-full rounded bg-slate-200"></div>
            <div class="h-2.5 w-full rounded-full bg-slate-200"></div>
            </div>
        </div>
    </template>

    <template v-else-if="type === 'menu'">
      <div class="grid grid-cols-4 gap-4 px-2">
        <div v-for="n in 8" :key="n" class="flex animate-pulse flex-col items-center gap-2">
          <div class="h-12 w-12 rounded-2xl bg-slate-200"></div>
          <div class="h-3 w-full rounded bg-slate-200"></div>
        </div>
      </div>
    </template>

    <template v-else-if="type === 'banner'">
      <div class="h-40 w-full animate-pulse rounded-[2rem] bg-slate-200"></div>
    </template>
  </div>
</template>

<script setup>
defineProps({
  loading: {
    type: Boolean,
    default: false
  },
  type: {
    type: String,
    default: 'card' // card, menu, banner
  },
  count: {
    type: Number,
    default: 3
  }
})
</script>