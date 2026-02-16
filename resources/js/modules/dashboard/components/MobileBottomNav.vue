<template>
  <nav class="fixed bottom-0 left-1/2 z-30 w-full max-w-md -translate-x-1/2 border-t border-slate-200 bg-white px-3 py-2">
    <div class="grid grid-cols-4 gap-1">
      <RouterLink
        v-for="item in navItems"
        :key="item.label"
        :to="item.to"
        class="flex min-h-14 flex-col items-center justify-center rounded-xl text-xs font-medium text-slate-500"
        :class="$route.path === item.to ? 'bg-teal-50 text-teal-700' : ''"
      >
        <span class="text-base">{{ item.icon }}</span>
        <span>{{ item.label }}</span>
      </RouterLink>
    </div>
  </nav>
</template>

<script setup>
const navItems = [
  { label: 'Beranda', to: '/', icon: '🏠' },
  { label: 'Promo', to: '/promo', icon: '🏷️' },
  { label: 'Aktivitas', to: '/ticket', icon: '🧾' },
  { label: 'Chat', to: '/chat', icon: '💬' }
]
</script>
