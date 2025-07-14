<script lang="ts" setup>
import type { Preset } from '@/types/preset'

const props = defineProps<{
  preset?: Preset
}>()

const title = computed(() => {
  return props.preset ? `Edit Preset: ${props.preset.name}` : 'Create New Preset'
})
</script>

<template>
  <USlideover
    :title="title"
  >
    <template #body>
      <PresetForm :preset="props.preset" />
    </template>
  </USlideover>
</template>
