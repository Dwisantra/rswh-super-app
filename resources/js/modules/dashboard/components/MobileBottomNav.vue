<template>
  <nav class="fixed bottom-0 left-1/2 z-30 w-full max-w-md -translate-x-1/2 border-t border-slate-200 bg-white px-4 py-2">
    <div class="grid grid-cols-4 gap-1 text-center text-xs text-slate-500">
      <RouterLink
        v-for="item in navItems"
        :key="item.label"
        :to="item.to"
        class="flex flex-col items-center gap-1 rounded-lg py-1"
        :class="$route.path === item.to ? 'text-rose-600 font-semibold' : ''"
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
