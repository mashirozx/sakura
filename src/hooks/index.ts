import { useRouter, useRoute } from 'vue-router'
import { useState, usePersistedState } from './state'
import { useProvider, useProviders, useInjector } from './store'
import { useIntlProvider, useIntl } from './intl'
import useWindowResize from './useWindowResize'
import useResizeObserver from './useResizeObserver'
import useReachElementSide from './useReachElementSide'
import { useElementRef, useElementRefs } from './useElementRef'
import useOffsetDistance from './useOffsetDistance'
import useMDCRipple from './mdc/useMDCRipple'
import useMessage, { useCommonMessages } from './useMessage'
import useTypewriterEffect from './useTypewriterEffect'
import useIntervalWatcher from './useIntervalWatcher'
import useKeepAliveWindowScrollTop from './useKeepAliveWindowScrollTop'
import useWindowScrollLock from './useWindowScrollLock'
import useScrollToElement from './useScrollToElement'

export {
  useState,
  usePersistedState,
  useProvider,
  useProviders,
  useInjector,
  useIntlProvider,
  useIntl,
  useRouter,
  useRoute,
  useWindowResize,
  useResizeObserver,
  useMDCRipple,
  useReachElementSide,
  useElementRef,
  useElementRefs,
  useOffsetDistance,
  useMessage,
  useCommonMessages,
  useTypewriterEffect,
  useIntervalWatcher,
  useKeepAliveWindowScrollTop,
  useWindowScrollLock,
  useScrollToElement,
}
