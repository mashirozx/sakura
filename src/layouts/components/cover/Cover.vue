<template>
  <div class="cover__container" :ref="setParallaxContainerRef" :style="styleController">
    <div class="background__wrapper">
      <div class="image__wrapper">
        <Image
          src="https://view.moezx.cc/images/2021/06/19/ca4748651c3c67e7e4c29c34fb13bc33.jpg"
          placeholder=""
          :avatar="false"
          alt=""
          :draggable="false"
          @error="handleImageError"
          @load="handleImageLoad"
        ></Image>
      </div>
      <div class="video__wrapper"></div>
    </div>
    <div class="content__wrapper">
      <div class="content__container">
        <div class="slogan__wrapper">
          <h1 class="typewriter">{{ sloganText }}<span class="cursor">&nbsp;</span></h1>
        </div>
        <div :class="['dialog__wrapper', { show: shouldShowSignatureDialog }]">
          <div class="quote__wrapper" v-if="quote">
            <span>
              <i class="icon fas fa-quote-left"></i>
              {{ quote }}
              <i class="icon fas fa-quote-right"></i>
            </span>
          </div>
          <div class="signature__wrapper" v-if="signature">
            <span>
              {{ signature }}
            </span>
          </div>
          <div class="social-media__wrapper">
            <div class="social-media__content">
              <div
                class="social-media-item__wrapper"
                v-for="(item, index) in socialMedia"
                :key="index"
              >
                <UiIcon :name="`social.${item.name}`"></UiIcon>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="mask__layer"></div>
  </div>
</template>

<script lang="ts">
import { defineComponent, watch, onActivated, onDeactivated, onMounted, onUnmounted } from 'vue'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { useTypewriterEffect, useState, useElementRef } from '@/hooks'
import sakuraOptions from '@/utils/sakuraOptions'

export default defineComponent({
  setup() {
    // sakura options data
    const socialMedia = Object.keys(sakuraOptions)
      .map((key) => {
        if (/^social\./.test(key)) {
          const match = key.match(/^social\.(.*)$/)
          if (!match || !sakuraOptions[key]) return null
          return {
            name: match[1],
            value: sakuraOptions[key],
          }
        } else {
          return null
        }
      })
      .filter((i) => i)

    const slogan = sakuraOptions['homepage.slogan']
    const quote = sakuraOptions['homepage.quote']
    const signature = sakuraOptions['homepage.signature']

    const [parallaxContainerRef, setParallaxContainerRef] = useElementRef()

    watch(parallaxContainerRef, (parallaxContainer) => {
      if (parallaxContainer instanceof Element) {
        const layersEloement = Array.prototype.slice.call(parallaxContainer.children)
        gsap.registerPlugin(ScrollTrigger)
        gsap.to(layersEloement[0], {
          scrollTrigger: {
            trigger: layersEloement[0],
            start: 'top top',
            end: 'bottom top',
            scrub: true,
          },
          y: '20%',
        })
      }
    })

    const [shouldShowSignatureDialog, setShouldShowSignatureDialog] = useState(false)
    const showSignatureDialog = () => {
      window.setTimeout(() => setShouldShowSignatureDialog(true), 700)
    }

    const [sloganText, doSloganEffect, clearText] = useTypewriterEffect(
      slogan.split(' '),
      ' ',
      100,
      showSignatureDialog
    )

    const [isSloganEffectCalled, setIsSloganEffectCalled] = useState(false)

    const initeSloganEffect = () => {
      if (!isSloganEffectCalled.value) {
        doSloganEffect()
        setIsSloganEffectCalled(true)
      }
    }

    const clearSloganEffect = () => {
      clearText()
      setShouldShowSignatureDialog(false)
      setIsSloganEffectCalled(false)
    }

    onMounted(() => window.setTimeout(() => initeSloganEffect(), 1000))
    onActivated(() => window.setTimeout(() => initeSloganEffect(), 1000))
    onUnmounted(() => clearSloganEffect())
    onDeactivated(() => clearSloganEffect())

    const handleImageError = () => {
      // window.setTimeout(() => doSloganEffect(), 1000)
    }
    const handleImageLoad = () => {
      // window.setTimeout(() => doSloganEffect(), 1000)
    }

    const styleController = () => {
      return {}
    }

    return {
      setParallaxContainerRef,
      styleController,
      sloganText,
      quote,
      signature,
      handleImageError,
      handleImageLoad,
      socialMedia,
      shouldShowSignatureDialog,
    }
  },
})
</script>

<style lang="scss" scoped>
@use '@/styles/mixins/polyfills';
$mobile-view-max-width: 800px;
.cover__container {
  width: 100%;
  height: 100%;
  overflow: hidden;
  position: relative;
  .background__wrapper {
    width: 100%;
    height: 100%;
    z-index: -1;
    .image__wrapper {
      width: 100%;
      height: 100%;
    }
  }
  .content__wrapper {
    position: absolute;
    top: calc(50% + 48px);
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1;
    .content__container {
      display: flex;
      flex-flow: column nowrap;
      align-items: center;
      .slogan__wrapper {
        .typewriter {
          // position: relative;
          margin: 0 auto;
          letter-spacing: 0.15em;
          max-width: 80vw;
          align-self: center;
          font-size: 60px;
          text-transform: uppercase;
          color: #ffffff;
          white-space: nowrap;
          .cursor {
            position: relative;
            &:after {
              content: '';
              position: absolute;
              bottom: 0;
              left: 0;
              width: 0.15em;
              height: 1.2em;
              background: orange;
              animation: blink-caret 1s step-end infinite;
            }
          }
          @media screen and (max-width: $mobile-view-max-width) {
            white-space: normal;
          }
          @keyframes blink-caret {
            from,
            to {
              background: transparent;
            }
            50% {
              background: orange;
            }
          }
        }
      }
      .dialog__wrapper {
        position: relative;
        padding: 12px;
        color: #eaeadf;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 10px;
        margin-top: 12px;
        // letter-spacing: 0.1em;
        font-size: 16px;
        line-height: 30px;
        align-self: flex-start;
        visibility: hidden;
        // width: 100%;
        @media screen and (max-width: $mobile-view-max-width) {
          display: none;
        }
        &.show {
          visibility: visible;
          animation: fadeIn /* animate.css */ 0.8s;
        }
        &:before {
          content: '';
          position: absolute;
          top: -30px;
          left: 60px;
          margin-left: -15px;
          border-width: 15px;
          border-style: solid;
          border-color: transparent transparent rgba(0, 0, 0, 0.5) transparent;
        }
        .quote__wrapper {
          width: 100%;
          text-align: left;
          // text-align-last: right;
          white-space: pre;
          .icon {
            &:first-child {
              padding-right: 6px;
            }
            &:last-child {
              padding-left: 6px;
            }
          }
        }
        .signature__wrapper {
          width: 100%;
          text-align: right;
        }
        .social-media__wrapper {
          margin-top: 6px;
          width: 100%;
          .social-media__content {
            width: 100%;
            display: flex;
            flex-flow: row wrap;
            justify-content: flex-end;
            align-items: center;
            @include polyfills.flex-gap(20px, 'row wrap');
            .social-media-item__wrapper {
              width: 24px;
              height: 24px;
              display: flex;
              align-items: center;
              justify-content: center;
            }
          }
        }
      }
    }
  }
  .mask__layer {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    &:before {
      background-image: url('@/assets/masks/dot.png');
      content: '';
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      background-attachment: fixed;
    }
  }
}
</style>
