import type axios from 'axios'
import type Echo from 'laravel-echo'
import type Pusher from 'pusher-js'

declare global {
  interface Window {
    axios: typeof axios
    Pusher: typeof Pusher
    Echo: Echo<'reverb'>
  }
}

export {}
