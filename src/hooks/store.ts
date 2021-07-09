import { provide, inject, InjectionKey, App } from 'vue'

export interface FunctionalStore<T extends object> {
  (...args: any[]): T
  token?: symbol
  root?: T
}

export function useProvider<T extends object>(func: FunctionalStore<T>): T {
  if (!func.token) func.token = Symbol('functional store')
  const depends = func()
  provide(func.token, depends)
  return depends
}

export function useProviders(...funcs: FunctionalStore<any>[]): any {
  funcs.forEach((func) => {
    if (!func.token) func.token = Symbol('functional store')
    provide(func.token, func())
  })
}

export const storeProviderPlugin = {
  install: (app: App, options: FunctionalStore<any>[]): void => {
    options.forEach((func) => {
      if (!func.token) func.token = Symbol('functional store')
      app.provide(func.token, func())
    })
  },
}

type InjectType = 'root' | 'optional'

// TODO: readonly to ref
export function useInjector<T extends object>(func: FunctionalStore<T>, type?: InjectType): any {
  const token = func.token as InjectionKey<symbol>
  const root = func.root

  switch (type) {
    case 'optional':
      // console.log('optional')
      return inject<T>(token) || func.root || null
    case 'root':
      // console.log('root')
      if (!func.root) func.root = func()
      return func.root
    default:
      // console.log('default')
      if (inject(token)) {
        return inject<T>(token)
      }
      if (root) return func.root
      throw new Error(
        `Hook function '${func.name}' was not provided from upper leval component by calling 'useProvider'.`
      )
  }
}
