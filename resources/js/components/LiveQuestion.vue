<script lang="ts" setup>
const props = defineProps<{
  question: string
  username: string
  color: string
}>()
</script>

<template>
  <div class="relative px-20 py-12 bg-neutral-900 border-8 border-neutral-700 shadow-lg rounded-xl overflow-hidden text-5xl">
    <div class="relative text-white">
      {{ props.question }}
    </div>

    <div class="mt-8 relative font-semibold text-right font-sofia" :style="{ color: props.color }">
      {{ props.username }}
    </div>
  </div>
</template>
