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
          <h1 class="typewriter">{{ sloganText }}</h1>
        </div>
        <div class="dialog__wrapper">
          <div class="signature__wrapper">
            <span>
              <i class="fas fa-quote-left"></i>
              You got to put the past behind you before you can move on.
              <i class="fas fa-quote-right"></i>
            </span>
          </div>
          <div class="social-media__wrapper">
            <div
              class="social-media-item__wrapper"
              v-for="(item, index) in socialMedia"
              :key="index"
            >
              <UiIcon :name="item.name"></UiIcon>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, watch } from 'vue'
import { gsap } from 'gsap'
import { useTypewriterEffect } from '@/hooks'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { useElementRef } from '@/hooks'

export default defineComponent({
  setup() {
    const slogan = 'Hello, world!'

    const [parallaxContainerRef, setParallaxContainerRef] = useElementRef()

    watch(parallaxContainerRef, (parallaxContainer) => {
      if (parallaxContainer instanceof Element) {
        const layersEloement = Array.prototype.slice.call(parallaxContainer.children)
        gsap.registerPlugin(ScrollTrigger)
        gsap.to(layersEloement[0], {
          scrollTrigger: {
            trigger: layersEloement[0],
            start: 'top top',
            scrub: true,
          },
          y: '20%',
        })
      }
    })

    const [sloganText, doSloganEffect] = useTypewriterEffect([slogan])

    const handleImageError = () => {
      window.setTimeout(() => doSloganEffect(), 1000)
    }
    const handleImageLoad = () => {
      window.setTimeout(() => doSloganEffect(), 1000)
    }

    const config = (window as any).InitState.config
    const socialMedia = Object.keys(config)
      .map((key) => {
        if (/^social\./.test(key)) {
          const match = key.match(/^social\.(.*)$/)
          if (!match || !config[key]) return null
          return {
            name: match[1],
            value: config[key],
          }
        } else {
          return null
        }
      })
      .filter((i) => i)
    console.log(socialMedia)

    const styleController = () => {
      return {}
    }

    return {
      setParallaxContainerRef,
      styleController,
      slogan,
      handleImageError,
      handleImageLoad,
      sloganText,
      socialMedia,
    }
  },
})
</script>

<style lang="scss" scoped>
.cover__container {
  width: 100%;
  height: 100%;
  overflow: hidden;
  position: relative;
  .background__wrapper {
    width: 100%;
    height: 100%;
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
        font-size: 36px;
        text-transform: uppercase;
        color: #ffffff;
        .typewriter {
          overflow: hidden;
          border-right: 0.15em solid orange;
          white-space: nowrap;
          margin: 0 auto;
          letter-spacing: 0.15em;
          animation: blink-caret 0.75s step-end infinite;
        }

        @keyframes blink-caret {
          from,
          to {
            border-color: transparent;
          }
          50% {
            border-color: orange;
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
        // align-self: flex-start;
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
        .signature__wrapper {
          width: 100%;
          text-align: center;
        }
        .social-media__wrapper {
          width: 100%;
          display: flex;
          flex-flow: row wrap;
          justify-content: center;
          align-items: center;
          gap: 12px;
          .social-media-item__wrapper {
            width: 20px;
            height: 20px;
          }
        }
      }
    }
  }
}
</style>
