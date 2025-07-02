<script lang="ts" setup>
const confetti = useConfetti()

const { start, ready } = useTimeout(5_000, { controls: true, immediate: false })

onMounted(() => {
  window.Echo.channel('live')
    .listen('ConfettiExplode', () => {
      confetti.explode()
    })
    .listen('ConfettiLocked', () => {
      start()
    })
})
</script>

<template>
  <Transition name="slide-in">
    <ConfettiLocked v-if="!ready" class="fixed bottom-8 left-1/2 -translate-x-1/2 transform-y-0" />
  </Transition>

  <Confetti class="absolute top-0 left-1/2 -translate-x-1/2" />
</template>
