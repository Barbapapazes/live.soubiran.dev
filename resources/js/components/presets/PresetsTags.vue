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
  <div class="space-y-4">
    <div v-for="(_, index) in value" :key="index">
      <UContextMenu :items="items(index) ">
        <PresetTag v-model="value[index]" :errors="props.errors?.[index]" class="grid grid-cols-2 gap-4" />
      </UContextMenu>
    </div>

    <UButton block label="Add Tag" variant="soft" @click="addTag" />
  </div>
</template>
