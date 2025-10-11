<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <div class="bg-white rounded-lg shadow-md p-8">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
          <router-link to="/" class="inline-flex items-center space-x-2 mb-4">
            <div class="w-12 h-12 bg-primary-600 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-2xl">IM</span>
            </div>
            <span class="text-2xl font-bold text-gray-900">IndoMarket</span>
          </router-link>
          <h2 class="text-2xl font-bold text-gray-900">Sign in to your account</h2>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
          {{ error }}
        </div>

        <!-- Login Form -->
        <form @submit.prevent="handleLogin">
          <div class="space-y-4">
            <div>
              <label for="email" class="form-label">Email</label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                class="form-input"
                placeholder="you@example.com"
              />
            </div>

            <div>
              <label for="password" class="form-label">Password</label>
              <input
                id="password"
                v-model="form.password"
                type="password"
                required
                class="form-input"
                placeholder="••••••••"
              />
            </div>

            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <input
                  id="remember"
                  v-model="form.remember"
                  type="checkbox"
                  class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                />
                <label for="remember" class="ml-2 block text-sm text-gray-900">
                  Remember me
                </label>
              </div>

              <div class="text-sm">
                <a href="#" class="font-medium text-primary-600 hover:text-primary-500">
                  Forgot password?
                </a>
              </div>
            </div>

            <button
              type="submit"
              :disabled="isLoading"
              class="w-full btn-primary disabled:opacity-50"
            >
              <span v-if="isLoading" class="flex items-center justify-center">
                <div class="spinner mr-2"></div>
                Signing in...
              </span>
              <span v-else>Sign in</span>
            </button>
          </div>
        </form>

        <!-- Register Link -->
        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Don't have an account?
            <router-link to="/register" class="font-medium text-primary-600 hover:text-primary-500">
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
