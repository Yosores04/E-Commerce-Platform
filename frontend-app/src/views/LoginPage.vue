<template>
  <div class="min-h-screen bg-gradient-to-br from-neutral-50 via-white to-accent-50/30 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <div class="bg-white border border-neutral-100 rounded-2xl shadow-xl p-8">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
          <router-link to="/" class="inline-flex flex-col items-center space-y-3 mb-4">
            <div class="w-16 h-16 bg-gradient-to-br from-primary-900 to-burgundy-900 rounded-xl flex items-center justify-center shadow-lg">
              <span class="text-3xl font-bold font-['Playfair_Display'] text-gold-400">X</span>
            </div>
            <span class="text-3xl font-bold font-['Playfair_Display'] bg-gradient-to-r from-primary-900 via-burgundy-900 to-primary-800 bg-clip-text text-transparent">Xerxia</span>
          </router-link>
          <h2 class="text-2xl font-bold text-primary-900">Sign in to your account</h2>
          <p class="text-sm text-neutral-500 mt-2">Elegance in every purchase</p>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="mb-4 p-4 bg-burgundy-50 border border-burgundy-200 text-burgundy-800 rounded-lg">
          {{ error }}
        </div>

        <!-- Login Form -->
        <form @submit.prevent="handleLogin">
          <div class="space-y-4">
            <div>
              <label for="email" class="block text-sm font-semibold text-primary-900 mb-2">Email</label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-900 focus:border-transparent transition-all"
                placeholder="you@example.com"
              />
            </div>

            <div>
              <label for="password" class="block text-sm font-semibold text-primary-900 mb-2">Password</label>
              <input
                id="password"
                v-model="form.password"
                type="password"
                required
                class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-900 focus:border-transparent transition-all"
                placeholder="••••••••"
              />
            </div>

            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <input
                  id="remember"
                  v-model="form.remember"
                  type="checkbox"
                  class="h-4 w-4 text-primary-900 focus:ring-primary-900 border-neutral-300 rounded"
                />
                <label for="remember" class="ml-2 block text-sm text-neutral-700">
                  Remember me
                </label>
              </div>

              <div class="text-sm">
                <a href="#" class="font-medium text-primary-900 hover:text-gold-500 transition-colors">
                  Forgot password?
                </a>
              </div>
            </div>

            <button
              type="submit"
              :disabled="isLoading"
              class="w-full bg-gradient-to-r from-primary-900 via-burgundy-900 to-primary-800 text-white font-semibold py-3 px-4 rounded-lg hover:from-primary-800 hover:via-burgundy-800 hover:to-primary-700 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg"
            >
              <span v-if="isLoading" class="flex items-center justify-center">
                <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Signing in...
              </span>
              <span v-else>Sign in</span>
            </button>
          </div>
        </form>

        <!-- Register Link -->
        <div class="mt-6 text-center">
          <p class="text-sm text-neutral-600">
            Don't have an account?
            <router-link to="/register" class="font-semibold text-primary-900 hover:text-gold-500 transition-colors">
              Register here
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: '',
  remember: false
})
const isLoading = ref(false)
const error = ref('')

const handleLogin = async () => {
  isLoading.value = true
  error.value = ''
  
  try {
    await authStore.login({
      email: form.value.email,
      password: form.value.password
    })
    
    // Redirect to intended page or home
    const redirectTo = route.query.redirect || '/'
    router.push(redirectTo)
  } catch (err) {
    error.value = err.response?.data?.message || 'Invalid email or password'
  } finally {
    isLoading.value = false
  }
}
</script>
