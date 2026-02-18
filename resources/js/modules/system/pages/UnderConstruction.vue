<template>
    <div class="flex min-h-[70vh] items-center justify-center px-4 py-10">
        <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white/90 backdrop-blur shadow-xl p-8 text-center">
        <div class="flex justify-center">
            <img
                :src="resolvedGif"
                :alt="alt"
                class="w-44 h-44 object-contain drop-shadow-sm"
                loading="lazy"
            />
        </div>
        <h1 class="mt-6 text-2xl font-bold text-slate-800">
            Fitur Segera Hadir
        </h1>

        <p class="mt-2 text-sm leading-relaxed text-slate-500">
            Kami sedang menyiapkan fitur ini agar dapat digunakan dengan nyaman.
            Silakan kembali lagi nanti ya 👋
        </p>

        <div class="my-6 h-px bg-slate-200"></div>

        <RouterLink
        to="/"
        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-teal-600 px-5 py-3 font-semibold text-white shadow-md transition active:scale-[0.97] hover:bg-teal-700"
        >
        <font-awesome-icon icon="arrow-left" /> Kembali ke Beranda
        </RouterLink>

    </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import devBuild from '@/assets/gif/building-developing.gif'

const props = defineProps({
  alt: { type: String, default: 'Under Construction' }
})

const resolvedGif = computed(() => props.gifSrc || devBuild)
</script>
