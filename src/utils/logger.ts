export default function logger(type: keyof Console, ...args: any) {
  if (Object.hasOwnProperty.call(console, type)) {
    if (import.meta.env.DEV) {
      console[type](...args)
    }
  } else {
    if (import.meta.env.DEV) {
      throw new Error(`No such property \`${type}\` in object console`)
    }
  }
}
