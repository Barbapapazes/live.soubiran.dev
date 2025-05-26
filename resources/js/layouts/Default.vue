<script lang="ts" setup>
defineSlots<{
  default: (props?: any) => any
}>()
</script>

<template>
  <main class="bg-white text-slate-950 font-sans px-60 py-100">
    <div class="flex flex-col gap-20">
      <slot />
    </div>

    <Footer class="absolute left-1/2 bottom-20 -translate-x-1/2" />
  </main>
</template>
