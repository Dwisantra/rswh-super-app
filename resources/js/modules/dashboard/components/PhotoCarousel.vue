<template>
  <section class="rounded-2xl bg-white p-3 shadow-sm">
    <div class="relative overflow-hidden rounded-xl">
      <img :src="slides[activeIndex].image" :alt="slides[activeIndex].title" class="h-40 w-full object-cover" />
      <div class="absolute inset-0 bg-gradient-to-t from-slate-900/65 to-transparent" />
      <div class="absolute bottom-3 left-3 right-3 text-white">
        <p class="text-lg font-bold leading-tight">{{ slides[activeIndex].title }}</p>
        <p class="text-xs text-slate-100">{{ slides[activeIndex].subtitle }}</p>
      </div>
    </div>

    <div class="mt-3 flex justify-center gap-1.5">
      <button
        v-for="(_, idx) in slides"
        :key="idx"
        class="h-2.5 rounded-full transition-all"
        :class="idx === activeIndex ? 'w-6 bg-teal-600' : 'w-2.5 bg-slate-300'"
        @click="goTo(idx)"
      />
    </div>
  </section>
</template>

<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue'

const props = defineProps({
  slides: {
    type: Array,
    default: () => []
  },
  autoplayMs: {
    type: Number,
    default: 3500
  }
})

const activeIndex = ref(0)
let timer = null

const goTo = (idx) => {
  activeIndex.value = idx
}

onMounted(() => {
  if (!props.slides.length) return
  timer = setInterval(() => {
    activeIndex.value = (activeIndex.value + 1) % props.slides.length
  }, props.autoplayMs)
})

onBeforeUnmount(() => {
  if (timer) clearInterval(timer)
})
</script>
