<script lang="ts" setup>
import type { Preset } from '@/types/preset'
import { socials } from '@/utils/socials'

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
      <Header :headline="props.preset.data.end.headline" :title="props.preset.data.end.title" />

      <Description :text="props.preset.data.end.description" />

      <Socials class="absolute left-1/2 bottom-48 -translate-x-1/2" :socials="socials" />
    </Default>
  </App>
</template>
