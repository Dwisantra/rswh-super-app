<template>
  <section class="space-y-4">
    <article
      v-for="section in sections"
      :key="section.id"
      class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
    >
      <div class="mb-3">
        <h2 class="text-xl font-semibold text-slate-900">
          {{ section.title }}
        </h2>
        <p class="text-sm text-slate-500">
          {{ section.description }}
        </p>
      </div>

      <!-- GRID MENU -->
      <div class="menu-grid">
        <RouterLink
          v-for="item in section.items"
          :key="item.id"
          :to="item.to"
          class="menu-card rounded-2xl border p-3 transition hover:shadow-sm"
          :class="item.tone"
        >
          <div class="menu-icon">
            <font-awesome-icon :icon="item.icon" />
          </div>
          <p class="menu-title text-base font-semibold leading-tight">
            {{ item.label }}
          </p>
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
</script>
