import { createApp } from 'vue'
import App from './App.vue'
import { router } from './router'
import './main.css'
import './echo'

createApp(App)
  .use(router)
  .mount('#app')
