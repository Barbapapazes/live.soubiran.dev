<script lang="ts" setup>
import type { Social } from './Social.vue'

const props = defineProps<{
  socials: Social[]
}>()
</script>

<template>
  <ol class="flex items-center gap-6">
    <li v-for="social in props.socials" :key="social.name">
      <Social :social="social" />
    </li>
  </ol>
</template>
