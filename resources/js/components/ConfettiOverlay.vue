<script lang="ts" setup>
import { Confetti as NeoConfetti } from '@neoconfetti/vanilla'

const props = defineProps<{
  colors: string[]
}>()

onMounted(() => {
  window.Echo.channel('live')
    .listen('CelebrationOver', useThrottleFn(() => {
      confetti().explode()
    }, 2500, true))
})

onUnmounted(() => {
  window.Echo.leave('live')
})

function confetti() {
  const child = createConfettiChild()
  const _confetti = new NeoConfetti(child, {
    particleCount: 225,
    particleSize: 30,
    duration: 5000,
    colors: props.colors,
    stageHeight: window.innerHeight - 30,
    stageWidth: window.innerWidth,
  })

  function explode() {
    _confetti
      .explode()
      .then(() => {
        child.remove()
      })
  }

  return { explode }
}

function createConfettiChild() {
  const confetti = document.querySelector('#confetti')

  if (!confetti) {
    throw new Error('Confetti container not found')
  }

  const child = document.createElement('div')
  child.id = `confetti-${Math.random().toString(36).substring(2, 15)}`
  confetti.appendChild(child)
  return child
}
</script>

<template>
  <Confetti class="absolute top-0 left-1/2 -translate-x-1/2" />
</template>
