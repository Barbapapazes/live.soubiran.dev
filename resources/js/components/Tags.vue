<script lang="ts" setup>
import type { PresetTag } from '@/types/preset'

const props = defineProps<{
  tags: PresetTag[]
}>()
</script>

<template>
  <ol class="flex items-center gap-6">
    <li v-for="tag in props.tags" :key="tag.name">
      <Tag :tag="tag" />
    </li>
  </ol>
</template>
