<script lang="ts" setup>
import type { Preset } from '@/types/preset'

const props = defineProps<{
  preset: Preset
}>()

onMounted(() => {
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
  <App :colors="colors">
    <Default>
      <Header :headline="props.preset.data.start.headline" :title="props.preset.data.start.title" />

      <Tags :tags="props.preset.data.tags" />
    </Default>
  </App>
</template>
