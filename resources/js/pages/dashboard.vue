<script lang="ts" setup>
import type { Preset } from '@/types/preset'

const props = defineProps<{
  presets: Preset[]
}>()
</script>

<template>
  <UApp>
    <UMain class="isolate">
      <UPage>
        <UPageBody>
          <UContainer>
            <div class="grid grid-cols-4 gap-8">
              <CelebrateQuickAction />
            </div>

            <div class="mt-8 grid grid-cols-5 gap-8 items-start">
              <PresetsCard class="col-span-2" :presets="props.presets" />

              <ChatCard class="col-span-3" />
            </div>
          </UContainer>
        </UPageBody>
      </UPage>
    </UMain>
  </UApp>
</template>
