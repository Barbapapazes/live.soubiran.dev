import ui from '@nuxt/ui/vite'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'
import fonts from 'unplugin-fonts/vite'
import { defineConfig } from 'vite'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/js/app.ts'],
      refresh: true,
    }),

    vue(),
    ui({
      inertia: true,
      components: {
        dts: 'resources/js/components.d.ts',
        dirs: [
          'resources/js/components',
          'resources/js/layouts',
        ],
      },
      autoImport: {
        dts: 'resources/js/auto-imports.d.ts',
        dirs: [
          'resources/js/composables',
          'resources/js/utils',
        ],
        imports: [
          'vue',
          '@vueuse/core',
          {
            from: '@inertiajs/vue3',
            imports: ['useForm', 'router'],
          },
        ],
      },
    }),
    fonts({
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
  ],

  resolve: {
    alias: {
      '@': '/resources/js',
    },
  },
})
