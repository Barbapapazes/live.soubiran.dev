<script lang="ts">
export interface Tag {
  name: string
  color: string
}
</script>

<script lang="ts" setup>
const props = defineProps<{
  tag: Tag
}>()
</script>

<template>
  <div class="text-5xl px-10 py-2 border border-(--color) bg-(--color)/5 rounded-md" :style="`--color: ${props.tag.color};`">
    {{ props.tag.name }}
  </div>
</template>
