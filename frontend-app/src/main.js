import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import { useAuthStore } from './stores/auth'
import './style.css'
import App from './App.vue'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)

// Initialize auth before mounting (non-blocking)
const authStore = useAuthStore()
if (authStore.token) {
  // Try to fetch profile but don't block app mounting
  authStore.initAuth().catch(() => {
    // If init fails, just continue - user can still browse public pages
    console.log('Auth initialization skipped')
  })
}

app.mount('#app')

