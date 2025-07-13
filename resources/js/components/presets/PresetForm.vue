<script lang="ts" setup>
import type { Preset } from '@/types/preset'

const props = defineProps<{
  preset?: Preset
}>()

const form = useForm({
  name: props.preset?.name || '',
  tags: (props.preset?.data.tags || []) as { name: string, color: string }[],
  start: {
    headline: props.preset?.data.start.headline || '',
    title: props.preset?.data.start.title || '',
  },
  break: {
    headline: props.preset?.data.break.headline || '',
    title: props.preset?.data.break.title || '',
  },
  end: {
    headline: props.preset?.data.end.headline || '',
    title: props.preset?.data.end.title || '',
    description: props.preset?.data.end.description || '',
  },
})

const tagsErrors = computed(() => {
  return Object.entries(form.errors).reduce((acc, [key, message]) => {
    const match = /^start.tags\.(\d+)\.(\w+)$/.exec(key)
    if (match) {
      const index = Number(match[1])
      const field = match[2]
      acc[index] ??= {}
      acc[index][field] = message
    }
    return acc
  }, {} as Record<number, Record<string, string>>)
})

function onSubmit() {
  if (props.preset) {
    form.put(`/presets/${props.preset.id}`, {
      only: ['presets'],
    })
  }
  else {
    form.post('/presets', {
      only: ['presets'],
    })
  }
}
</script>

<template>
  <form @submit.prevent="onSubmit">
    <UFormField label="Name" name="name" :error="form.errors.name" required>
      <UInput v-model="form.name" placeholder="Enter the preset name" class="w-full" />
    </UFormField>

    <PresetsTags v-model="form.tags" :errors="tagsErrors" />

    <USeparator label="Start" />

    <UFormField label="Headline" name="headline" :error="form.errors['start.headline']" required>
      <UInput v-model="form.start.headline" placeholder="Enter the preset headline" class="w-full" />
    </UFormField>

    <UFormField label="Title" name="title" :error="form.errors['start.title']" required>
      <UInput v-model="form.start.title" placeholder="Enter the preset title" class="w-full" />
    </UFormField>

    <USeparator label="Break" />

    <UFormField label="Headline" name="break.headline" :error="form.errors['break.headline']" required>
      <UInput v-model="form.break.headline" placeholder="Enter the break headline" class="w-full" />
    </UFormField>

    <UFormField label="Title" name="break.title" :error="form.errors['break.title']" required>
      <UInput v-model="form.break.title" placeholder="Enter the break title" class="w-full" />
    </UFormField>

    <USeparator label="End" />

    <UFormField label="Headline" name="end.headline" :error="form.errors['end.headline']" required>
      <UInput v-model="form.end.headline" placeholder="Enter the end headline" class="w-full" />
    </UFormField>

    <UFormField label="Title" name="end.title" :error="form.errors['end.title']" required>
      <UInput v-model="form.end.title" placeholder="Enter the end title" class="w-full" />
    </UFormField>

    <UFormField label="Description" name="end.description" :error="form.errors['end.description']" required>
      <UInput v-model="form.end.description" placeholder="Enter the end description" class="w-full" />
    </UFormField>

    <div class="flex flex-row items-center justify-end gap-4">
      <span v-if="form.recentlySuccessful" class="text-sm text-muted">
        Created.
      </span>

      <UButton type="submit" :label="props.preset ? 'Update Preset' : 'Create Preset'" :loading="form.processing" />
    </div>
  </form>
</template>
