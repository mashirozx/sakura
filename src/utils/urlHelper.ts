export const isUrl = (url: string): { state: boolean; msg?: any } => {
  try {
    new URL(url)
  } catch (error) {
    return {
      state: false,
      msg: error,
    }
  }
  return {
    state: true,
  }
}
