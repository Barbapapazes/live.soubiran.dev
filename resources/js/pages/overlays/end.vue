<script lang="ts" setup>
import { socials } from '../../utils/socials'

const headline = 'Estéban Soubiran - On écrit du code pour faire des trucs cool.'
const title = 'Le stream est terminé'
const description = 'Replay disponible sur YouTube prochainement.'
</script>

<template>
  <App>
    <Default>
      <Header :headline="headline" :title="title" />

      <Description :text="description" />

      <Socials class="absolute left-1/2 bottom-48 -translate-x-1/2" :socials="socials" />
    </Default>
  </App>
</template>
