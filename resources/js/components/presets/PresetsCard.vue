<script lang="ts" setup>
import type { TableRow } from '@nuxt/ui'
import type { Preset } from '@/types/preset'
import PresetFormSlideover from '@/components/presets/PresetFormSlideover.vue'

const props = defineProps<{
  presets: Preset[]
}>()

const overlay = useOverlay()
function onPresetSelect(preset: TableRow<Preset>) {
  overlay
    .create(PresetFormSlideover, {
      props: {
        preset: preset.original,
      },
      destroyOnClose: true,
    })
    .open()
}

function onCreatePreset() {
  overlay
    .create(PresetFormSlideover, {
      destroyOnClose: true,
    })
    .open()
}
</script>

<template>
  <DashboardCard title="Presets" :ui="{ body: 'p-0 sm:p-0' }">
    <template #actions>
      <UButton label="New" color="neutral" variant="ghost" @click="onCreatePreset" />
    </template>

    <PresetsTable :presets="props.presets" @select="onPresetSelect" />
  </DashboardCard>
</template>
