import Tailwind from '@tailwindcss/vite'
import Vue from '@vitejs/plugin-vue'
import Laravel from 'laravel-vite-plugin'
import AutoImport from 'unplugin-auto-import/vite'
import Fonts from 'unplugin-fonts/vite'
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

    Fonts({
      google: {
        families: [
          {
            name: 'DM Sans',
            styles: 'ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000',
          },
          {
            name: 'DM Mono',
            styles: 'ital,wght@0,300;0,400;0,500;1,300;1,400;1,500',
          },
          {
            name: 'Sofia Sans',
            styles: 'ital,wght@0,1..1000;1,1..1000',
          },
        ],
      },
    }),

    AutoImport({
      dts: 'resources/js/auto-imports.d.ts',
      dirs: [
        'resources/js/composables',
        'resources/js/utils',
      ],
      imports: [
        'vue',
        '@vueuse/core',
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
