<script lang="ts" setup>
import type { Tag } from './Tag.vue'

const props = defineProps<{
  tags: Tag[]
}>()
</script>

<template>
  <ol class="flex items-center gap-6">
    <li v-for="tag in props.tags" :key="tag.name">
      <Tag :tag="tag" />
    </li>
  </ol>
</template>
