<script lang="ts" setup>
interface ShowLiveQuestion {
  question: string
  username: string
  color: string
}

const liveQuestion = ref<ShowLiveQuestion | null>(null)
onMounted(() => {
  window.Echo.channel('live')
    .listen('ShowLiveQuestion', (event: ShowLiveQuestion) => {
      liveQuestion.value = null

      setTimeout(() => liveQuestion.value = event, 300)
    })
    .listen('HideLiveQuestion', () => {
      liveQuestion.value = null
    })
})
</script>

<template>
  <Transition name="slide-in">
    <div v-if="liveQuestion" class="absolute inset-x-0 bottom-0">
      <div class="max-w-3/4 px-20 pb-20 leading-[1.25]">
        <LiveQuestion
          :question="liveQuestion.question"
          :username="liveQuestion.username"
          :color="liveQuestion.color"
        />
      </div>
    </div>
  </Transition>
</template>
