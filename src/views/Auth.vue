<template>
  <h1> <span>Auth</span> </h1>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useRoute } from 'vue-router'
import { auth } from '@/store'
import { useInjector } from '@/hooks'
import camelcaseKeys from 'camelcase-keys'

export default defineComponent({
  name: 'Auth',
  setup() {
    const route = useRoute()
    const { setApplicationPassword } = useInjector(auth)
    const data = camelcaseKeys(route.query)

    if (
      ['siteUrl', 'userLogin', 'password']
        .map((key) => Object.hasOwnProperty.call(data, key))
        .find((state) => !state)
    ) {
      setApplicationPassword(data)
    }
  },
})
</script>
