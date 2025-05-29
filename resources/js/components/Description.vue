<script lang="ts" setup>
const props = defineProps<{
  text: string
}>()
</script>

<template>
  <p class="text-slate-700 text-5xl">
    {{ props.text }}
  </p>
</template>
