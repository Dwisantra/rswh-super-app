<script setup>
import { computed } from "vue"

const props = defineProps({
  totalItems: Number,
  itemsPerPage: {
    type: Number,
    default: 10
  },
  modelValue: Number
})

const emit = defineEmits(["update:modelValue"])

const totalPages = computed(() =>
  Math.ceil(props.totalItems / props.itemsPerPage)
)

const changePage = (page) => {
  if (page < 1 || page > totalPages.value) return
  emit("update:modelValue", page)
}
</script>

<template>
  <div v-if="totalPages > 1" class="flex items-center justify-center gap-2 mt-6">

    <!-- Prev -->
    <button
      @click="changePage(modelValue - 1)"
      class="px-3 py-1.5 rounded-lg border text-sm bg-white hover:bg-slate-100"
      :disabled="modelValue === 1"
    >
      ‹
    </button>

    <!-- Number -->
    <button
      v-for="page in totalPages"
      :key="page"
      @click="changePage(page)"
      class="px-3 py-1.5 rounded-lg text-sm border transition"
      :class="page === modelValue
        ? 'bg-teal-600 text-white border-teal-600'
        : 'bg-white hover:bg-slate-100'"
    >
      {{ page }}
    </button>

    <!-- Next -->
    <button
      @click="changePage(modelValue + 1)"
      class="px-3 py-1.5 rounded-lg border text-sm bg-white hover:bg-slate-100"
      :disabled="modelValue === totalPages"
    >
      ›
    </button>

  </div>
</template>
