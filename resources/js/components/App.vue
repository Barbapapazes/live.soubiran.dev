<script lang="ts" setup>
const props = defineProps<{
  colors: string[]
}>()
</script>

<template>
  <div class="h-screen w-screen">
    <slot />

    <LiveQuestionOverlay />
    <ConfettiOverlay :colors="props.colors" />
  </div>
</template>
