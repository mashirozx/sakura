<template>
  <Base>
    <div class="main__content">
      <div class="content__wrapper">
        <div class="single__wrapper">
          <Content :slug="slug" pageType="normal"></Content>
        </div>
      </div>
    </div>
  </Base>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useRoute, useIntl } from '@/hooks'
import Base from '@/layouts/Base.vue'
import Content from '@/components/content/Content.vue'

export default defineComponent({
  components: { Content, Base },
  setup() {
    const intl = useIntl()
    const route = useRoute()
    const { slug } = route.params

    return { slug }
  },
})
</script>

<style lang="scss" scoped>
.main__content {
  .content__wrapper {
    width: 100%;
  }
}
</style>
