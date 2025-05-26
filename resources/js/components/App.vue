<script lang="ts" setup>
const confetti = useConfetti()
function explode() {
  confetti.explode()
}
</script>

<template>
  <div class="h-screen w-screen">
    <slot />

    <Confetti class="absolute top-0 left-1/2 -translate-x-1/2" />
  </div>
</template>
