<script lang="ts" setup>
const props = defineProps<{
  question: string
  username: string
  color: string
}>()
</script>

<template>
  <div class="p-4 bg-accented rounded-2xl shadow-md">
    <div class="px-20 py-12 bg-neutral-900 shadow-xl rounded-xl text-5xl">
      <div class="text-default">
        {{ props.question }}
      </div>

      <div class="mt-8 font-semibold text-right font-sofia opacity-80" :style="{ color: props.color }">
        {{ props.username }}
      </div>
    </div>
  </div>
</template>
