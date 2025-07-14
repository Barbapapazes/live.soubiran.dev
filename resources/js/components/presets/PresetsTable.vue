<script lang="ts" setup>
import type { TableColumn } from '@nuxt/ui'
import type { Preset } from '@/types/preset'
import { h } from 'vue'

const props = defineProps<{
  presets: Preset[]
}>()

const UButton = resolveComponent('UButton')
const Switch = resolveComponent('USwitch')

const columns: TableColumn<Preset>[] = [
  {
    id: 'expand',
    cell: ({ row }) =>
      h(UButton, {
        'size': 'sm',
        'color': 'neutral',
        'variant': 'ghost',
        'icon': 'i-ph-caret-down',
        'square': true,
        'aria-label': 'Expand',
        'ui': {
          leadingIcon: [
            'transition-transform',
            row.getIsExpanded() ? 'duration-200 rotate-180' : '',
          ],
        },
        'onClick': () => row.toggleExpanded(),
      }),
  },
  {
    accessorKey: 'name',
    header: 'Name',
  },
  {
    accessorKey: 'activated_at',
    header: 'Activated',
    cell: ({ row }) => {
      const isActivated = row.getValue('activated_at') !== null

      const form = useForm({})
      return h(Switch, {
        'loading': form.processing,
        'ui': { base: isActivated ? 'cursor-not-allowed' : '' },
        'modelValue': isActivated,
        'onUpdate:modelValue': (value: boolean) => {
          if (value) {
            form.post(`/presets/${row.original.id}/activate`)
          }
        },
      })
    },
  },
]
</script>

<template>
  <UTable :data="props.presets" :columns="columns">
    <template #expanded="{ row }">
      <pre>{{ row.original }}</pre>
    </template>
  </UTable>
</template>
