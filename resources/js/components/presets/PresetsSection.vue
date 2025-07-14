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
  <section class="space-y-4">
    <div class="flex flex-row items-center justify-between">
      <h2 class="font-semibold text-lg">
        Presets
      </h2>

      <UButton label="Create Preset" color="neutral" variant="subtle" @click="onCreatePreset" />
    </div>

    <PresetsTable :presets="props.presets" @select="onPresetSelect" />
  </section>
</template>
