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
          <h2 class="text-2xl font-bold text-gray-900">Create your account</h2>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
          <p v-if="typeof error === 'string'">{{ error }}</p>
          <ul v-else class="list-disc list-inside">
            <li v-for="(messages, field) in error" :key="field">
              {{ messages[0] }}
            </li>
          </ul>
        </div>

        <!-- Register Form -->
        <form @submit.prevent="handleRegister">
          <div class="space-y-4">
            <div>
              <label for="name" class="form-label">Full Name</label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="form-input"
                placeholder="John Doe"
              />
            </div>

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
              <p class="mt-1 text-xs text-gray-500">At least 8 characters</p>
            </div>

            <div>
              <label for="password_confirmation" class="form-label">Confirm Password</label>
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                required
                class="form-input"
                placeholder="••••••••"
              />
            </div>

            <div>
              <label for="phone" class="form-label">Phone Number</label>
              <input
                id="phone"
                v-model="form.phone"
                type="tel"
                class="form-input"
                placeholder="+62 812 3456 7890"
              />
            </div>

            <div class="flex items-start">
              <div class="flex items-center h-5">
                <input
                  id="terms"
                  v-model="form.agreeToTerms"
                  type="checkbox"
                  required
                  class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                />
              </div>
              <div class="ml-3 text-sm">
                <label for="terms" class="text-gray-700">
                  I agree to the
                  <a href="#" class="text-primary-600 hover:text-primary-500">Terms of Service</a>
                  and
                  <a href="#" class="text-primary-600 hover:text-primary-500">Privacy Policy</a>
                </label>
              </div>
            </div>

            <button
              type="submit"
              :disabled="isLoading"
              class="w-full btn-primary disabled:opacity-50"
            >
              <span v-if="isLoading" class="flex items-center justify-center">
                <div class="spinner mr-2"></div>
                Creating account...
              </span>
              <span v-else>Create account</span>
            </button>
          </div>
        </form>

        <!-- Login Link -->
        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Already have an account?
            <router-link to="/login" class="font-medium text-primary-600 hover:text-primary-500">
              Sign in here
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  phone: '',
  agreeToTerms: false
})
const isLoading = ref(false)
const error = ref('')

const handleRegister = async () => {
  if (form.value.password !== form.value.password_confirmation) {
    error.value = 'Passwords do not match'
    return
  }
  
  if (!form.value.agreeToTerms) {
    error.value = 'You must agree to the terms and conditions'
    return
  }
  
  isLoading.value = true
  error.value = ''
  
  try {
    await authStore.register({
      name: form.value.name,
      email: form.value.email,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation,
      phone: form.value.phone
    })
    
    router.push('/')
  } catch (err) {
    error.value = err.response?.data?.errors || err.response?.data?.message || 'Registration failed'
  } finally {
    isLoading.value = false
  }
}
</script>
