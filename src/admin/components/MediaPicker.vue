<template>
  <div class="picker__container">
    <div class="row__wrapper--input">
      <div class="input__wrapper">
        <OutlinedInput v-model:content="userInput" label="Input remote URL here"></OutlinedInput>
      </div>
      <div class="button__wrapper" @click="add">
        <NormalButton icon="fas fas fa-link" context="Add" :contained="true"></NormalButton>
      </div>
      <div class="button__wrapper" @click="open">
        <NormalButton icon="fas fa-box-open" context="Pick" :contained="true"></NormalButton>
      </div>
    </div>
    <div class="row__wrapper--preview">
      <div class="image__box" v-for="(item, index) in selection" :key="index">
        <div class="image__wrapper">
          <Image :src="item.url" :avatar="false" :draggable="false"></Image>
        </div>
        <div class="delete__button" @click="del(index)">
          <span><i class="fas fa-trash-alt"></i></span>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch, Ref } from 'vue'
import { cloneDeep, remove } from 'lodash'
import { useMessage, useIntl } from '@/hooks'
import uniqueHash from '@/utils/uniqueHash'
import { isUrl } from '@/utils/urlHelper'
import NormalButton from '@/components/buttons/NormalButton.vue'
import OutlinedInput from '@/components/inputs/OutlinedInput.vue'

export default defineComponent({
  components: { NormalButton, OutlinedInput },
  props: {
    title: { type: String, default: 'Select Media' },
    button: { type: String, default: 'Use this media' },
    type: { type: String, default: 'image' }, // video
    multiple: { type: Boolean, default: true },
    selection: { type: Array, default: () => [] }, // [{url,id}]
  },
  emits: ['update:selection'],
  setup(props, { emit }) {
    const addMessage = useMessage()
    const intl = useIntl()

    const selection: Ref<{ id: number; url: string }[]> = ref(
      props.selection as { id: number; url: string }[]
    )

    const userInput = ref('')

    const frame = (window as any).wp.media({
      id: `media-frame-${uniqueHash()}`,
      title: props.title,
      multiple: props.multiple ? 'add' : false,
      allowLocalEdits: true,
      displaySettings: true,
      displayUserSettings: true,
      library: {
        // author: uid, // specific user-posted attachment
        type: props.type,
      },
      button: { text: props.button },
    })

    frame.on('select', () => {
      const result = (frame.state().get('selection') as any[]).map((item) => {
        const { id, url } = item.attributes
        return { id: id as number, url: url as string }
      })

      const selected = cloneDeep(selection.value)

      // Delete unchecked items
      remove(selected, (item) => item.id > 0 && result.map((i) => i.id).indexOf(item.id) < 0)

      // Delete existing items
      remove(result, (item) => selected.map((i) => i.id).indexOf(item.id) >= 0)

      selection.value = [...selected, ...result]
    })

    // frame.on('close', () => {
    //   console.log(frame.state().get('selection').toJSON())
    // })

    frame.on('open', () => {
      const result = frame.state().get('selection')
      const preSelectIds = selection.value
        .map((item) => (item.id ? (window as any).wp.media.attachment(item.id) : NaN))
        .filter((attachment) => attachment)
      result.add(preSelectIds)
    })

    const open = () => {
      frame.open()
    }

    const add = () => {
      const url = userInput.value
      if (isUrl(url).state) {
        if (selection.value.map((item) => item.url).indexOf(url) < 0) {
          selection.value.push({ id: 0, url })
          userInput.value = ''
        } else {
          addMessage({
            title: intl.formatMessage({
              id: 'messages.admin.uplicateUrls',
              defaultMessage: 'Duplicate URLs',
            }),
            type: 'warning',
          })
        }
      } else {
        addMessage({
          title: intl.formatMessage({
            id: 'messages.admin.invalidUrl',
            defaultMessage: 'Invalid URL',
          }),
          type: 'error',
        })
      }
    }

    const del = (index: number) => {
      remove(selection.value, (item, itemIndex) => index === itemIndex)
    }

    watch(
      selection,
      (value) => {
        if (!props.multiple && value.length > 1) {
          selection.value = selection.value.slice(-1)
          console.log(selection.value.length)
        }
        console.log(selection.value)
        emit('update:selection', selection.value)
      },
      { deep: true }
    )

    return { open, add, del, userInput, selection }
  },
})
</script>

<style lang="scss" scoped>
@use '../variables';
.picker__container {
  width: 100%;
  display: flex;
  flex-flow: column nowrap;
  justify-content: flex-start;
  align-items: center;
  gap: 12px;
  > * {
    width: 100%;
  }
  > .row__wrapper {
    &--input {
      display: flex;
      flex-flow: row nowrap;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
      > .input__wrapper {
        flex: 1 1 auto;
        width: 100%;
      }
      > .button__wrapper {
        flex: 0 0 auto;
      }
      @media screen and (max-width: variables.$small-mobile-max-width) {
        flex-flow: row wrap;
        justify-content: flex-start;
        > .input__wrapper {
          flex: 0 0 auto;
        }
      }
    }
    &--preview {
      display: flex;
      flex-flow: row wrap;
      justify-content: flex-start;
      align-items: center;
      gap: 12px;
      > .image__box {
        position: relative;
        overflow: hidden;
        > .image__wrapper {
          width: 200px;
          height: 200px;
          border: 1px solid #bdbdbd;
          border-radius: 5px;
          overflow: hidden;
        }
        > .delete__button {
          position: absolute;
          top: 0;
          right: 0;
          width: 30px;
          height: 30px;
          background: #757575;
          font-size: 16px;
          color: #ffffff;
          border-radius: 0 5px 0 5px;
          display: flex;
          justify-content: center;
          align-items: center;
          cursor: pointer;
          opacity: 1;
          transition: all 0.3s ease-in-out;
        }
        &:hover {
          .delete__button {
            opacity: 1;
          }
        }
      }
    }
  }
}
</style>
