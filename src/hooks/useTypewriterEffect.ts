/**
 * https://codepen.io/gavra/pen/tEpzn
 */
import { ref, watch, Ref } from 'vue'
const useTypewriterEffect = (strings: string[], speed = 100): [Ref<string>, () => void] => {
  const textRef = ref('')

  const typewriterEffect = () => {
    const aText = strings
    const iSpeed = speed // time delay of print out
    let iIndex = 0 // start printing array at this posision
    let iArrLength = aText[0].length // the length of the text array
    const iScrollAt = 20 // start scrolling up at this many lines
    let iTextPos = 0 // initialise text position
    let iRow // initialise current row
    const typewriter = () => {
      iRow = Math.max(0, iIndex - iScrollAt)
      // const destination = element
      while (iRow < iIndex) {
        textRef.value += aText[iRow++] + '<br />'
      }
      textRef.value = aText[iIndex].substring(0, iTextPos) // + '_'
      if (iTextPos++ == iArrLength) {
        iTextPos = 0
        iIndex++
        if (iIndex != aText.length) {
          iArrLength = aText[iIndex].length
          window.setTimeout(typewriter, 500)
        }
      } else {
        window.setTimeout(typewriter, iSpeed)
      }
    }
    typewriter()
  }

  return [textRef, typewriterEffect]
}

export default useTypewriterEffect
