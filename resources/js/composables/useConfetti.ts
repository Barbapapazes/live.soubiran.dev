import { Confetti } from '@neoconfetti/vanilla'

function _useConfetti() {
  function explode() {
    new Confetti(createConfettiChild(), {
      particleCount: 225,
      particleSize: 30,
      duration: 5000,
      colors: tags.map(tag => tag.color),
      stageHeight: window.innerHeight - 30,
      stageWidth: window.innerWidth,
    })
      .explode()
  }

  function createConfettiChild(): HTMLDivElement {
    const confetti = document.querySelector('#confetti')

    if (!confetti) {
      throw new Error('Confetti container not found')
    }

    const child = document.createElement('div')
    child.id = `confetti-${Math.random().toString(36).substring(2, 15)}`
    confetti.appendChild(child)

    return child
  }

  return {
    explode: useThrottleFn(explode, 2500, true),
  }
}

export const useConfetti = createSharedComposable(_useConfetti)
