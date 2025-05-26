<script lang="ts" setup>
import { tags } from '../../utils/tags'

const headline = 'Estéban Soubiran - On écrit du code pour faire des trucs cool.'
const title = 'Le stream va démarrer...'
</script>

<template>
  <App>
    <Default>
      <Header :headline="headline" :title="title" />

      <Tags :tags="tags" />
    </Default>
  </App>
</template>
