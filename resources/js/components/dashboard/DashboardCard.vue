<script lang="ts" setup>
import type { CardProps } from '@nuxt/ui'

const props = defineProps<{
  title: string
  ui?: CardProps['ui']
}>()
defineSlots<{
  default: (props?: any) => any
  actions: (props?: any) => any
}>()
</script>

<template>
  <UCard :ui="{ header: 'flex flex-row items-center justify-between', ...props.ui }" variant="subtle">
    <template #header>
      <h2 class="font-semibold text-lg">
        {{ props.title }}
      </h2>

      <slot name="actions" />
    </template>

    <slot />
  </UCard>
</template>
