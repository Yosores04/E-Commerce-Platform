<template>
  <button
    @click="goBack"
    class="inline-flex items-center space-x-2 px-4 py-2 text-gray-700 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-50 hover:border-wine transition-all"
  >
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    <span class="font-medium">Back</span>
  </button>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const goBack = () => {
  router.back()
}
</script>
