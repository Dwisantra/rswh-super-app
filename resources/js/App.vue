<script setup>
import { computed, onMounted } from 'vue'
import useConnection from './composables/useConnection'
import useServer from './composables/useServer'
import { autoRequestNotificationPermissionOnFirstOpen } from './services/notifications'

const { online } = useConnection()
const { serverAlive } = useServer()

const alertMessage = computed(() => {
    if (!online.value) {
        return 'Tidak ada koneksi internet'
    }

    if (!serverAlive.value) {
        return 'Server tidak terhubung'
    }

    return ''
})

onMounted(() => {
  autoRequestNotificationPermissionOnFirstOpen()
})
</script>

<template>

  <transition name="slide-down">
      <div v-if="alertMessage" class="connection-alert" role="status" aria-live="polite">
          {{ alertMessage }}
      </div>
  </transition>
  
  <router-view />
</template>
