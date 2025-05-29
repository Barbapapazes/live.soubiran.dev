<script lang="ts" setup>
import { tags } from '../../utils/tags'

const headline = 'Estéban Soubiran - Je reviens dans quelques minutes !'
const title = 'Pause eau en cours...'
</script>

<template>
  <App>
    <Default>
      <Header :headline="headline" :title="title" />

      <Tags :tags="tags" />
    </Default>
  </App>
</template>
