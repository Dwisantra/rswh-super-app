<template>
  <Transition name="fade">
    <div v-if="modelValue" class="fixed inset-0 z-[10000] flex items-end justify-center bg-black/50 p-4">
      <div class="w-full max-w-md rounded-t-3xl bg-white p-6 shadow-xl animate-slide-up">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-slate-900">Pilih Pasien</h3>
          <button @click="$emit('update:modelValue', false)" class="text-slate-400 p-2">
             <font-awesome-icon icon="times" />
          </button>
        </div>
        
        <div class="max-h-80 overflow-y-auto space-y-3 pr-1">
          <div 
            v-for="member in familyMembers" 
            :key="member.id"
            @click="handleSelect(member)"
            class="flex items-center justify-between p-4 rounded-2xl border transition-all cursor-pointer"
            :class="selectedId === member.id ? 'border-teal-500 bg-teal-50' : 'border-slate-100 hover:bg-slate-50'"
          >
            <div class="flex items-center gap-3">
              <div class="h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 font-bold">
                {{ member.name.charAt(0) }}
              </div>
              <div>
                <p class="font-bold text-slate-900">{{ member.name }}</p>
                <p class="text-xs text-slate-500">RM: {{ member.norm }} • {{ member.relation }}</p>
              </div>
            </div>
            <font-awesome-icon v-if="selectedId === member.id" icon="check-circle" class="text-teal-600" />
          </div>
        </div>
        
        <!-- <RouterLink 
          to="/keluarga/tambah" 
          class="mt-6 block w-full rounded-2xl bg-slate-100 py-4 text-center text-sm font-bold text-teal-700 hover:bg-slate-200"
        >
          + Tambah Keluarga Baru
        </RouterLink> -->
      </div>
    </div>
  </Transition>
</template>

<script setup>
defineProps({
  modelValue: Boolean,
  familyMembers: Array,
  selectedId: Number
})

const emit = defineEmits(['update:modelValue', 'onSelect'])

const handleSelect = (patient) => {
  emit('onSelect', patient)
  emit('update:modelValue', false)
}
</script>

<style scoped>
.animate-slide-up {
  animation: slideUp 0.3s ease-out;
}
@keyframes slideUp {
  from { transform: translateY(100%); }
  to { transform: translateY(0); }
}
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>