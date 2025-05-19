<script lang="ts" setup>
const props = defineProps<{
  text: string
}>()
</script>

<template>
  <div class="text-5xl text-slate-500 font-light font-sofia">
    {{ props.text }}
  </div>
</template>
