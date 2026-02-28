<template>
  <div class="carousel-wrapper">
    <div 
      ref="scrollContainer"
      class="flex overflow-x-auto snap-x snap-mandatory hide-scrollbar"
      @scroll="handleScroll"
    >
      <div 
        v-for="(item, index) in items" 
        :key="index"
        class="min-w-full snap-center px-1"
      >
        <slot :item="item" :index="index"></slot>
      </div>
    </div>

    <div v-if="items.length > 1" class="flex justify-center gap-1.5 mt-3">
      <div 
        v-for="(_, index) in items" 
        :key="index"
        class="h-1.5 rounded-full transition-all duration-300"
        :class="activeIndex === index ? 'w-4 bg-teal-600' : 'w-1.5 bg-slate-200'"
      ></div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  items: {
    type: Array,
    default: () => []
  }
})

const activeIndex = ref(0)
const scrollContainer = ref(null)

const handleScroll = (event) => {
  const container = event.target
  const width = container.offsetWidth
  if (width > 0) {
    activeIndex.value = Math.round(container.scrollLeft / width)
  }
}
</script>