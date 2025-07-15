<script lang="ts" setup>
const props = defineProps<{
  emoji: string
  label: string
}>()
</script>

<template>
  <UCard as="button" variant="subtle" class="relative group text-lg font-semibold cursor-pointer">
    <div class="absolute blur-3xl rounded-full size-28 bg-amber-500/20 group-hover:bg-amber-500/40 -bottom-6 -right-2 transition duration-300" />

    <div class="relative flex flex-row items-center gap-2">
      <span>
        {{ props.emoji }}
      </span>
      <span class="text-toned">
        {{ props.label }}
      </span>
    </div>
  </UCard>
</template>
