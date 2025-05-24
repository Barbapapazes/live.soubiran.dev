/// <reference types="vite/client" />
/// <reference types="unplugin-vue-router/client" />

interface ImportMetaEnv {
  readonly VITE_INALIA_API_KEY: string
  readonly VITE_INALIA_ENDPOINT: string
  readonly VITE_INALIA_USERNAME: string
  readonly VITE_INALIA_TALK_NUMBER: number
}

interface ImportMeta {
  readonly env: ImportMetaEnv
}
