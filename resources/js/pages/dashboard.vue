<script lang="ts" setup>
interface ReceivedMessage {
  message: string
  username: string
  color: string
}

const messages = ref<ReceivedMessage[]>([])

onMounted(() => {
  window.Echo.private('chat')
    .listen('ReceivedMessage', (event: ReceivedMessage) => {
      messages.value.push(event)
    })
})
const reverseMessages = computed(() => {
  return messages.value.slice().reverse()
})

const showLiveQuestionForm = useForm({
  question: '',
  username: '',
  color: '',
})
function showLiveQuestion(message: ReceivedMessage) {
  showLiveQuestionForm.question = message.message
  showLiveQuestionForm.username = message.username
  showLiveQuestionForm.color = message.color

  showLiveQuestionForm.post('/live-question')
}

const hideLiveQuestionForm = useForm({})
function hideLiveQuestion() {
  hideLiveQuestionForm.delete('/live-question')
}
</script>

<template>
  <main>
    <ul>
      <li v-for="(msg, index) in reverseMessages" :key="index">
        <button @click="showLiveQuestion(msg)">
          {{ msg.message }}
        </button>

        <span :style="{ color: msg.color }">
          <strong>{{ msg.username }}</strong>
        </span>
      </li>
    </ul>

    <button @click="hideLiveQuestion">
      Hide Live Question
    </button>
  </main>
</template>
