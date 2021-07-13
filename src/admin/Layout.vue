<template>
  <div class="layout">
    <div class="wrapper">
      <Image
        src="https://view.moezx.cc/images/2021/07/08/121a00d4db8142384418382abf529364.gif"
      ></Image>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import Image from '@/components/image/Image.vue'

export default defineComponent({
  components: { Image },
  setup() {},
})
</script>

<style lang="scss" scoped>
.wrapper {
  width: 200px;
  height: 200px;
}
</style>
