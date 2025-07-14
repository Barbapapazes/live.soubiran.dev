<script lang="ts" setup>
import type { ReceivedMessage } from '@/types/chat'

const messages = ref<ReceivedMessage[]>([])

onMounted(() => {
  window.Echo.private('chat')
    .listen('ReceivedMessage', (event: ReceivedMessage) => {
      messages.value.push(event)
    })
})

const reverseMessages = computed(() => {
  return messages.value
    .slice()
    .reverse()
})

function showLiveQuestion(message: ReceivedMessage) {
  useForm({
    question: message.message,
    username: message.username,
    color: message.color,
  })
    .post('/live-question')
}
</script>

<template>
  <ul v-if="messages.length">
    <li v-for="(msg, index) in reverseMessages" :key="index">
      <button @click="showLiveQuestion(msg)">
        {{ msg.message }}
      </button>

      <span :style="{ color: msg.color }">
        <strong>{{ msg.username }}</strong>
      </span>
    </li>
  </ul>

  <div v-else class="text-muted text-sm">
    No messages yet.
  </div>
</template>
