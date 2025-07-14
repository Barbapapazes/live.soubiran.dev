<script lang="ts" setup>
const hideForm = useForm({})
function hide() {
  hideForm.delete('/live-question')
}
</script>

<template>
  <DashboardCard title="Chat">
    <template #actions>
      <div class="flex items-center gap-2">
        <span v-if="hideForm.recentlySuccessful" class="text-muted text-sm">
          Hidden.
        </span>

        <UButton label="Hide" variant="ghost" color="error" :loading="hideForm.processing" @click="hide" />
      </div>
    </template>

    <Chat />
  </DashboardCard>
</template>
