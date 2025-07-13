<script lang="ts">
export interface PresetTag {
  name: string
  color: string
}
</script>

<script lang="ts" setup>
const props = defineProps<{
  errors?: Record<string, string>
}>()

const value = defineModel<PresetTag>({ required: true })
</script>

<template>
  <div>
    <UFormField label="Tag Name" name="tag-name" :error="props.errors?.name" required>
      <UInput v-model="value.name" placeholder="Enter the tag name" class="w-full" />
    </UFormField>

    <UFormField label="Tag Color" name="tag-color" :error="props.errors?.color" required>
      <UInput v-model="value.color" placeholder="Enter the tag color" class="w-full" />
    </UFormField>
  </div>
</template>
