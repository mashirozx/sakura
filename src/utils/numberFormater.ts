export default class {
  public locales: string | string[]

  constructor(locales: string | string[] = 'zh-CN') {
    this.locales = locales
  }

  /**
   * 億とか万とかに変換したいときはNumberFormat
   * https://zenn.dev/terrierscript/articles/2021-02-21-java-script-number-format-compact
   */
  public fullFormat(number: number): string {
    const formatter = new Intl.NumberFormat(this.locales, {
      notation: 'compact',
      useGrouping: false,
      maximumFractionDigits: 0,
    })
    const result: any[] = []
    const fmt = (number: number): any => {
      const [num, notation] = formatter.formatToParts(number) // how to handle BigInt?
      const numberStr = number.toString()
      if (notation === undefined) {
        return [...result, numberStr].join('')
      }
      const dig = num.value.length
      const value = numberStr.slice(0, dig)
      const next = Number(numberStr.slice(dig))
      result.push(`${value}${notation.value}`)
      return fmt(next)
    }
    return fmt(number)
  }

  // Only notation:compact
  public format(number: number): string {
    const fmt = new Intl.NumberFormat(this.locales, {
      notation: 'compact',
    })

    return fmt.format(number)
  }
}
