<script lang="ts" setup>
const props = defineProps<{
  text: string
}>()
</script>

<template>
  <p class="text-slate-500 text-6xl">
    {{ props.text }}
  </p>
</template>
