import { useRouter, useRoute } from 'vue-router'
import { useState, usePersistedState } from './state'
import { useProvider, useProviders, useInjector } from './store'
import { useIntlProvider, useIntl } from './intl'
import useWindowResize from './useWindowResize'
import useResizeObserver from './useResizeObserver'
import { useMDCRipple, useMDCDialog, useMDCTextField } from './mdc'
import useReachElementSide from './useReachElementSide'
import { useElementRef, useElementRefs } from './useElementRef'
import useOffsetDistance from './useOffsetDistance'

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
  useMDCDialog,
  useMDCTextField,
  useReachElementSide,
  useElementRef,
  useElementRefs,
  useOffsetDistance,
}
