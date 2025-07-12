<script lang="ts" setup>
const confetti = useConfetti()

onMounted(() => {
  window.Echo.channel('live')
    .listen('CelebrationOver', () => {
      confetti.explode()
    })
})
</script>

<template>
  <Confetti class="absolute top-0 left-1/2 -translate-x-1/2" />
</template>
