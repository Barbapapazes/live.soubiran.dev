<script lang="ts" setup>
import type { Preset } from '@/types/preset'

const props = defineProps<{
  preset?: Preset
}>()
const emits = defineEmits<{
  close: [void]
}>()

const title = computed(() => {
  return props.preset ? `Edit Preset` : 'New Preset'
})

function onSubmit() {
  emits('close')
}
</script>

<template>
  <USlideover
    :title="title"
  >
    <template #body>
      <PresetForm :preset="props.preset" @submit="onSubmit" />
    </template>
  </USlideover>
</template>
