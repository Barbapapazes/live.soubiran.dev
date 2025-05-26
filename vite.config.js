import Tailwind from '@tailwindcss/vite'
import Vue from '@vitejs/plugin-vue'
import Laravel from 'laravel-vite-plugin'
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { defineConfig } from 'vite'

export default defineConfig({
  plugins: [
    Laravel({
      input: ['resources/js/app.ts'],
      refresh: true,
    }),

    Vue(),
    Tailwind(),

    AutoImport({
      dts: 'resources/js/auto-imports.d.ts',
      dirs: [
        'resources/js/composables',
        'resources/js/utils',
      ],
      imports: [
        'vue',
      ],
    }),
    Components({
      dts: 'resources/js/components.d.ts',
      dirs: [
        'resources/js/components',
        'resources/js/layouts',
      ],
    }),
  ],
})
