<script lang="ts" setup>
const props = defineProps<{
  headline: string
  title: string
}>()
</script>

<template>
  <header>
    <Headline :text="props.headline" />

    <Title :title="props.title" />
  </header>
</template>
