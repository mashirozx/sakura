<template>
  <div class="recaptcha__container">
    <div
      class="g-recaptcha"
      :data-sitekey="sitekey"
      data-theme="dark"
      data-size="normal"
      :ref="setGRef"
    ></div>
  </div>
</template>

<script lang="ts">
import { defineComponent, watch, onMounted, onUnmounted } from 'vue'
import { useElementRef, useState } from '@/hooks'
import camelcaseKeys from 'camelcase-keys'

export default defineComponent({
  emits: ['verified'],
  setup(props, { emit }) {
    const sitekey = camelcaseKeys(window.InitState).recaptchaSiteKey
    const [gRef, setGRef] = useElementRef()
    const [idRef, setIdRef] = useState(NaN)
    const [tokenRef, setTokenRef] = useState('')

    watch(gRef, (gEl) => {
      if (gEl) {
        setIdRef(grecaptcha.render(gEl, { sitekey, theme: 'light' }))
      }
    })

    onMounted(() => {
      let timer = setInterval(() => {
        try {
          const _token = tokenRef.value
          const token = grecaptcha.getResponse(idRef.value)
          if (token && token !== _token) {
            setTokenRef(token)
            console.log(token)
            emit('verified', token)
          }
        } catch (e) {}
      }, 1000)

      onUnmounted(() => {
        clearInterval(timer)
      })
    })

    return { sitekey, setGRef }
  },
})
</script>
