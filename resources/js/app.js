
import './bootstrap'
import { createApp } from 'vue'
import router from './router'
import { i18n } from './i18n'


import App from './App.vue'

import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'
import '@fortawesome/fontawesome-free/css/all.min.css'

createApp(App)
  .use(router)
  .use(i18n)
  .mount('#app')
