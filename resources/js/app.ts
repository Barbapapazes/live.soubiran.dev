import type { DefineComponent } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { createApp, h } from 'vue'
import './bootstrap'
import './echo'
import '../css/app.css'

createInertiaApp({
  resolve: (name) => {
    const pages = import.meta.glob<DefineComponent>('./pages/**/*.vue', { eager: true })
    return pages[`./pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el)
  },
})
