<script lang="ts" setup>
import type { Preset } from '@/types/preset'

const props = defineProps<{
  preset: Preset
}>()

onMounted(() => {
  // Override the default body element to ensure it is white.
  document.querySelector('body')!.style.backgroundColor = '#ffffff'

  window.Echo.channel('live')
    .listen('PresetActivated', (event: { preset: Preset }) => {
      router.replace({
        props: currentProps => ({ ...currentProps, preset: event.preset }),
      })
    })
    .listen('PresetUpdated', (event: { preset: Preset }) => {
      router.replace({
        props: currentProps => ({ ...currentProps, preset: event.preset }),
      })
    })
})

const colors = computed(() => {
  return props.preset.data.tags.map(tag => tag.color)
})
</script>

<template>
  <App :colors="colors" />
</template>
