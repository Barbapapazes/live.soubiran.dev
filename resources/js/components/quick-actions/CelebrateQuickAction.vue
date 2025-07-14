<script lang="ts" setup>
function celebrate() {
  useForm({}).post('/celebrate')
}
</script>

<template>
  <QuickAction emoji="🎊" label="Celebrate" @click="celebrate" />
</template>
