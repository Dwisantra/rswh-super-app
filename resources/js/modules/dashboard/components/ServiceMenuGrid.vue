<template>
  <section class="space-y-4">
    <article v-for="section in sections" :key="section.id" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
      <div class="mb-3">
        <h2 class="text-xl font-semibold text-slate-900">{{ section.title }}</h2>
        <p class="text-sm text-slate-500">{{ section.description }}</p>
      </div>

      <div class="grid gap-3" :class="columnClass(section.columns)">
        <RouterLink
          v-for="item in section.items"
          :key="item.id"
          :to="item.to"
          class="rounded-2xl border p-3 transition hover:shadow-sm"
          :class="item.tone"
        >
          <div class="mb-2 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/80 text-xl">{{ item.icon }}</div>
          <p class="text-base font-semibold leading-tight">{{ item.label }}</p>
          <p class="mt-1 text-xs opacity-80">{{ item.subtitle }}</p>
        </RouterLink>
      </div>
    </article>
  </section>
</template>

<script setup>
defineProps({
  sections: {
    type: Array,
    default: () => []
  }
})

const columnClass = (columns) => {
  if (columns === 3) return 'grid-cols-3'
  if (columns === 1) return 'grid-cols-1'
  return 'grid-cols-2'
}
</script>
