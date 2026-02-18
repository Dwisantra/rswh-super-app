<template>
  <section class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">
    <div class="flex justify-center">
      <img
        :src="resolvedGif"
        :alt="alt"
        class="w-40 h-40 object-contain"
        loading="lazy"
      />
      
    </div>

    <p class="mt-4 text-2xl font-bold text-slate-800">{{ title }}</p>
    <p class="mt-1 text-base">{{ description }}</p>

    <RouterLink
      v-if="actionText && to"
      :to="to"
      class="mt-5 inline-block rounded-xl bg-teal-600 px-4 py-2 font-semibold text-white"
    >
      {{ actionText }}
    </RouterLink>

    <slot name="action" />
  </section>
</template>

<script setup>
import { computed } from 'vue'
import emptyGif from '@/assets/gif/empty-box.gif'

const props = defineProps({
  title: { type: String, default: 'Tidak ada data' },
  description: { type: String, default: 'Belum ada data yang tersedia' },
  actionText: { type: String, default: '' },
  to: { type: String, default: '' },
  gifSrc: { type: String, default: '' },
  alt: { type: String, default: 'empty state' }
})

const resolvedGif = computed(() => props.gifSrc || emptyGif)
</script>
