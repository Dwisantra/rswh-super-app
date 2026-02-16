<template>
  <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
    <div class="mb-3 flex items-center justify-between">
      <h2 class="text-lg font-semibold text-slate-900">Menu Layanan</h2>
      <span class="text-xs text-slate-500">Dummy FE</span>
    </div>

    <div class="space-y-2">
      <RouterLink
        v-for="menu in menus"
        :key="menu.id"
        :to="menu.to"
        class="flex items-center gap-3 rounded-xl border border-slate-100 p-3 transition hover:bg-slate-50"
      >
        <div class="grid h-11 w-11 place-items-center rounded-xl text-xl" :class="menu.color">{{ menu.icon }}</div>
        <div>
          <p class="font-semibold text-slate-800">{{ menu.label }}</p>
          <p class="text-xs text-slate-500">{{ menu.description }}</p>
        </div>
      </RouterLink>
    </div>
  </section>
</template>

<script setup>
defineProps({
  menus: {
    type: Array,
    default: () => []
  }
})
</script>
