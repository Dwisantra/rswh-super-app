<template>
  <header class="sticky top-0 z-40 relative overflow-hidden bg-gradient-to-br from-teal-600 to-cyan-700 px-4 pb-8 pt-8 text-white shadow-lg">
    <div class="relative z-10">
      <div class="flex items-center mb-4" :class="urlTo ? 'justify-between' : 'gap-4'">
        <RouterLink v-if="backTo" :to="backTo" class="back-btn">
          <font-awesome-icon icon="arrow-left" />
        </RouterLink>

        <h1 class="text-xl font-bold tracking-tight">{{ title }}</h1>
        
        <RouterLink v-if="urlTo" :to="urlTo" class="back-btn">
            <font-awesome-icon icon="plus" class="text-xl" />
        </RouterLink>
      </div>
      <p class="text-sm text-cyan-50 opacity-90">{{ description }}</p>
    </div>
    <div class="absolute -right-6 -top-6 h-32 w-32 rounded-full bg-white/10"></div>
  </header>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    default: ''
  },
  backTo: {
    type: String,
    default: null
  },
  urlTo: {
    type: String,
    default: null
  }
  
})
</script>