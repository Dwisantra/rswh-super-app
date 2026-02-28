<template>
  <van-pull-refresh
    v-model="internalRefreshing"
    @refresh="handleRefresh"
    class="pull-refresh-container"
  >
    <template #pulling>
      <div class="indicator-wrapper">
        <p>Tarik untuk memperbarui...</p>
      </div>
    </template>

    <template #loosing>
      <div class="indicator-wrapper">
        <p>Lepaskan untuk memperbarui...</p>
      </div>
    </template>

    <template #loading>
      <div class="indicator-wrapper">
        <div class="spinner"></div>
      </div>
    </template>

    <template #success>
      <div class="indicator-wrapper text-teal-600 font-medium">
        <p>Berhasil diperbarui</p>
      </div>
    </template>

    <slot />
  </van-pull-refresh>
</template>

<script setup>
import { computed } from 'vue'
import { PullRefresh as VanPullRefresh } from 'vant'
import 'vant/lib/pull-refresh/index.css'

const props = defineProps({
  modelValue: Boolean
})
const emit = defineEmits(['update:modelValue', 'refresh'])

const internalRefreshing = computed({
  get: () => props.modelValue,
  set: (val) => emit('update:modelValue', val)
})

const handleRefresh = () => emit('refresh')
</script>

<style scoped>
/* 1. Memaksa area indikator Vant melebar 100% dan Center */

</style>