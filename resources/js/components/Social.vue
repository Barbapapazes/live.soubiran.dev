<script lang="ts">
export interface Social {
  name: string
  icon: string
}
</script>

<script lang="ts" setup>
const props = defineProps<{
  social: Social
}>()
</script>

<template>
  <div class="text-slate-500 flex items-center gap-4 text-3xl font-sofia font-light">
    <div :class="props.social.icon" class="size-6 grayscale-75" />
    <span>
      {{ props.social.name }}
    </span>
  </div>
</template>
