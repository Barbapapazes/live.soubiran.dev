<script lang="ts" setup>
import type { ContextMenuItem } from '@nuxt/ui'
import type { PresetTag } from '@/types/preset'

const props = defineProps<{
  errors?: Record<number, Record<string, string>>
}>()

const value = defineModel<PresetTag[]>({ required: true })

function addTag() {
  value.value.push({ name: '', color: '' })
}

function items(index: number): ContextMenuItem[] {
  return [
    {
      label: 'Remove Tag',
      icon: 'i-heroicons-trash-solid',
      color: 'error',
      onSelect: () => {
        value.value.splice(index, 1)
      },
    },
  ]
}
</script>

<template>
  <div>
    <div v-for="(_, index) in value" :key="index">
      <UContextMenu :items="items(index) ">
        <PresetTag v-model="value[index]" :errors="props.errors?.[index]" />
      </UContextMenu>
    </div>

    <UButton label="Add Tag" @click="addTag" />
  </div>
</template>
