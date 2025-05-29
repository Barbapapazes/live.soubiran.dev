import { Confetti } from '@neoconfetti/vanilla'

export default function useConfetti() {
  let confetti: Confetti | null = null
  onMounted(() => {
    const particleSize = 30

    confetti = new Confetti(document.querySelector('#confetti')!, {
      particleCount: 225,
      particleSize,
      duration: 5000,
      colors: tags.map(tag => tag.color),
      stageHeight: window.innerHeight - particleSize,
      stageWidth: window.innerWidth,
    })
  })

  function explode() {
    if (!confetti) {
      return
    }

    confetti.explode()
  }

  return {
    explode,
  }
}
