<script lang="ts" setup>
const props = defineProps<{
  title: string
}>()
</script>

<template>
  <h1 class="text-[12rem] font-semibold tracking-wide leading-tight">
    {{ props.title }}
  </h1>
</template>
