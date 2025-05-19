<script lang="ts" setup>
const headline = 'Estéban Soubiran - On écrit du code pour faire des trucs cool.'
const title = 'Le stream est terminé'
const description = 'Replay disponible sur YouTube prochainement.'
</script>

<template>
  <Default>
    <Header :headline="headline" :title="title" />

    <Description :text="description" />
  </Default>
</template>
