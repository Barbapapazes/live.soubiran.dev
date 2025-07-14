export interface PresetTag {
  name: string
  color: string
}

export interface Preset {
  id: number
  name: string
  data: {
    tags: PresetTag[]
    start: {
      headline: string
      title: string
    }
    break: {
        headline: string
        title: string
    },
    end: {
      headline: string
      title: string
      description: string
    }
  }
}
