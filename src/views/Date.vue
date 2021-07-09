<template>
  <h1>{{ $props.type }}</h1>
  <p>{{ msg }}</p>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useIntl } from '@/hooks'

export default defineComponent({
  props: {
    type: { type: String, default: 'Date' },
  },
  setup(props) {
    const intl = useIntl()

    const router = useRouter()
    const route = useRoute()

    // console.log(route)
    console.log(route.params)

    const msg = 'test'
    return { msg }
  },
})
</script>
