<script lang="ts" setup>
const confetti = useConfetti()
function explode() {
  confetti.explode()
}

onMounted(() => {
  window.Echo.channel('live')
    .listen('ConfettiExplode', () => {
      confetti.explode()
    })
})
</script>

<template>
  <div class="h-screen w-screen">
    <RouterView />

    <Confetti class="absolute top-0 left-1/2 -translate-x-1/2" />
    <button class="absolute" type="button" @click="explode">
      Explode
    </button>
  </div>
</template>
