<script lang="ts" setup>
import type { PresetTag } from '@/types/preset'

const props = defineProps<{
  tag: PresetTag
}>()
</script>

<template>
  <div class="text-5xl px-10 py-2 border border-(--color) bg-(--color)/5 rounded-md" :style="`--color: ${props.tag.color};`">
    {{ props.tag.name }}
  </div>
</template>
