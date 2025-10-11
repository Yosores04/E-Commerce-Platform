<template>
  <header class="bg-white shadow-md sticky top-0 z-50">
    <!-- Top bar -->
    <div class="bg-primary-600 text-white">
      <div class="container mx-auto px-4 py-2">
        <div class="flex justify-between items-center text-sm">
          <div>
            <span>Welcome to IndoMarket</span>
          </div>
          <div class="flex items-center space-x-4">
            <template v-if="authStore.isAuthenticated">
              <span>Hello, {{ authStore.user?.name }}</span>
              <router-link 
                v-if="authStore.hasRole('vendor')" 
                to="/vendor/dashboard"
                class="hover:text-primary-200"
              >
                Vendor Dashboard
              </router-link>
              <router-link 
                v-if="authStore.hasRole('admin')" 
                to="/admin/dashboard"
                class="hover:text-primary-200"
              >
                Admin Panel
              </router-link>
              <button @click="handleLogout" class="hover:text-primary-200">
                Logout
              </button>
            </template>
            <template v-else>
              <router-link to="/login" class="hover:text-primary-200">Login</router-link>
              <router-link to="/register" class="hover:text-primary-200">Register</router-link>
            </template>
          </div>
        </div>
      </div>
    </div>

    <!-- Main header -->
    <div class="container mx-auto px-4 py-4">
      <div class="flex items-center justify-between">
        <!-- Logo -->
        <router-link to="/" class="flex items-center space-x-2">
          <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
            <span class="text-white font-bold text-xl">IM</span>
          </div>
          <span class="text-2xl font-bold text-gray-900">IndoMarket</span>
        </router-link>

        <!-- Search bar -->
        <div class="flex-1 max-w-2xl mx-8">
          <form @submit.prevent="handleSearch" class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search for products..."
              class="w-full px-4 py-2 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
            <button
              type="submit"
              class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-primary-600 text-white px-4 py-1 rounded-md hover:bg-primary-700"
            >
              Search
            </button>
          </form>
        </div>

        <!-- Cart & User -->
        <div class="flex items-center space-x-6">
          <router-link 
            to="/orders" 
            v-if="authStore.isAuthenticated"
            class="flex items-center space-x-1 text-gray-700 hover:text-primary-600"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Orders</span>
          </router-link>

          <router-link to="/cart" class="relative flex items-center space-x-1 text-gray-700 hover:text-primary-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span>Cart</span>
            <span 
              v-if="cartStore.itemCount > 0"
              class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"
            >
              {{ cartStore.itemCount }}
            </span>
          </router-link>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="mt-4 border-t pt-4">
        <ul class="flex space-x-6 text-gray-700">
          <li>
            <router-link to="/" class="hover:text-primary-600">Home</router-link>
          </li>
          <li>
            <router-link to="/products" class="hover:text-primary-600">All Products</router-link>
          </li>
          <li>
            <a href="#" class="hover:text-primary-600">Categories</a>
          </li>
          <li>
            <a href="#" class="hover:text-primary-600">New Arrivals</a>
          </li>
          <li>
            <a href="#" class="hover:text-primary-600">Best Sellers</a>
          </li>
        </ul>
      </nav>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useCartStore } from '../stores/cart'

const router = useRouter()
const authStore = useAuthStore()
const cartStore = useCartStore()
const searchQuery = ref('')

// Fetch cart if authenticated
if (authStore.isAuthenticated) {
  cartStore.fetchCart()
}

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    router.push({ name: 'Products', query: { q: searchQuery.value } })
  }
}

const handleLogout = async () => {
  await authStore.logout()
  router.push('/')
}
</script>
