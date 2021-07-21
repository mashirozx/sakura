import { ref, watch, Ref } from 'vue'
/**
 * @param strings
 * @param speed
 * @param callback function to call when done
 * @returns [textRef, do(), done?]
 */
const useTypewriterEffect = (
  strings: string[],
  separator = '/n',
  speed = 100,
  callback?: () => void
): [Ref<string>, () => void, () => void, Ref<boolean>] => {
  const textRef = ref('')
  const done = ref(false)

  const typewriterEffect = () => {
    textRef.value = '' // reset
    done.value = false

    const aText = strings
    const iSpeed = speed // time delay of print out
    let iIndex = 0 // start printing array at this posision
    let iArrLength = aText[0].length // the length of the text array
    const iScrollAt = 20 // start scrolling up at this many lines
    let iTextPos = 0 // initialise text position
    let iRow // initialise current row
    let beforeText = '' // cache last line

    const typewriter = () => {
      iRow = Math.max(0, iIndex - iScrollAt)

      while (iRow < iIndex) {
        textRef.value += aText[iRow++] + separator
      }
      textRef.value = beforeText + aText[iIndex].substring(0, iTextPos) // + '_'
      if (iTextPos++ === iArrLength) {
        iTextPos = 0
        iIndex++
        if (iIndex !== aText.length) {
          iArrLength = aText[iIndex].length
          window.setTimeout(typewriter, 500)
          beforeText = textRef.value + separator
        }
      } else {
        window.setTimeout(typewriter, iSpeed)
      }
      if (iRow === iIndex - 1 && iIndex === aText.length) {
        done.value = true
        if (callback) callback()
      }
    }
    typewriter()
  }

  const clearTextRef = () => {
    textRef.value = ''
  }

  return [textRef, typewriterEffect, clearTextRef, done]
}

export default useTypewriterEffect
