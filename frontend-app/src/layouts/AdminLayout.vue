<template>
  <div class="min-h-screen flex bg-whitesmoke">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-neutral-200 flex flex-col">
      <!-- Logo & Brand -->
      <div class="p-6 border-b border-neutral-200">
        <router-link to="/" class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-gradient-to-br from-primary-900 to-burgundy-700 rounded-lg flex items-center justify-center">
            <span class="text-white font-bold text-xl">X</span>
          </div>
          <div>
            <h1 class="text-lg font-bold text-gray-900">Xerxia</h1>
            <p class="text-xs text-gray-500">Admin Panel</p>
          </div>
        </router-link>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 p-4 space-y-1">
        <router-link
          to="/admin/dashboard"
          class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all"
          :class="$route.path === '/admin/dashboard' 
            ? 'bg-wine text-white shadow-md' 
            : 'text-gray-700 hover:bg-neutral-100'"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          <span class="font-medium">Dashboard</span>
        </router-link>

        <router-link
          to="/admin/products"
          class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all"
          :class="$route.path.startsWith('/admin/products') 
            ? 'bg-wine text-white shadow-md' 
            : 'text-gray-700 hover:bg-neutral-100'"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
          <span class="font-medium">Products</span>
        </router-link>

        <router-link
          to="/admin/categories"
          class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all"
          :class="$route.path.startsWith('/admin/categories') 
            ? 'bg-wine text-white shadow-md' 
            : 'text-gray-700 hover:bg-neutral-100'"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
          </svg>
          <span class="font-medium">Categories</span>
        </router-link>

        <router-link
          to="/admin/orders"
          class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all"
          :class="$route.path.startsWith('/admin/orders') 
            ? 'bg-wine text-white shadow-md' 
            : 'text-gray-700 hover:bg-neutral-100'"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
          <span class="font-medium">Orders</span>
        </router-link>

        <router-link
          to="/admin/vendors"
          class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all"
          :class="$route.path.startsWith('/admin/vendors') 
            ? 'bg-wine text-white shadow-md' 
            : 'text-gray-700 hover:bg-neutral-100'"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <span class="font-medium">Users & Vendors</span>
        </router-link>

        <div class="pt-4 border-t border-neutral-200 mt-4">
          <router-link
            to="/admin/settings"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all"
            :class="$route.path.startsWith('/admin/settings') 
              ? 'bg-wine text-white shadow-md' 
              : 'text-gray-700 hover:bg-neutral-100'"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="font-medium">Settings</span>
          </router-link>
        </div>
      </nav>

      <!-- User Profile & Logout -->
      <div class="p-4 border-t border-neutral-200">
        <div class="flex items-center space-x-3 px-4 py-3 bg-neutral-50 rounded-lg mb-2">
          <div class="w-10 h-10 bg-gradient-to-br from-wine to-burgundy-600 rounded-full flex items-center justify-center">
            <span class="text-white font-semibold text-sm">{{ userInitials }}</span>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-900 truncate">{{ authStore.user?.name }}</p>
            <p class="text-xs text-gray-500">Administrator</p>
          </div>
        </div>
        
        <div class="space-y-1">
          <router-link
            to="/"
            class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-neutral-100 rounded-lg transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>View Store</span>
          </router-link>
          
          <button
            @click="handleLogout"
            class="flex items-center space-x-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors w-full"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span>Logout</span>
          </button>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <!-- Top Bar -->
      <header class="h-16 bg-white border-b border-neutral-200 flex items-center justify-between px-6">
        <div class="flex items-center space-x-4">
          <h2 class="text-xl font-bold text-gray-900">{{ pageTitle }}</h2>
        </div>
        
        <div class="flex items-center space-x-4">
          <!-- Notifications -->
          <button class="relative p-2 text-gray-600 hover:text-wine hover:bg-neutral-100 rounded-lg transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
          </button>
          
          <!-- Current Date & Time -->
          <div class="text-right">
            <p class="text-xs text-gray-500">{{ currentDate }}</p>
            <p class="text-xs font-semibold text-gray-700">{{ currentTime }}</p>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 overflow-y-auto">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const currentDate = ref('')
const currentTime = ref('')
let timeInterval = null

const pageTitle = computed(() => {
  const path = route.path
  if (path.includes('dashboard')) return 'Dashboard'
  if (path.includes('products')) return 'Products'
  if (path.includes('categories')) return 'Categories'
  if (path.includes('orders')) return 'Orders'
  if (path.includes('users')) return 'Users'
  if (path.includes('vendors')) return 'Vendors'
  if (path.includes('settings')) return 'Settings'
  return 'Admin Panel'
})

const userInitials = computed(() => {
  const name = authStore.user?.name || 'Admin'
  const parts = name.split(' ')
  if (parts.length >= 2) {
    return `${parts[0][0]}${parts[1][0]}`.toUpperCase()
  }
  return name.substring(0, 2).toUpperCase()
})

const updateTime = () => {
  const now = new Date()
  currentDate.value = now.toLocaleDateString('en-PH', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
  currentTime.value = now.toLocaleTimeString('en-PH', {
    hour: '2-digit',
    minute: '2-digit'
  })
}

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}

onMounted(() => {
  updateTime()
  timeInterval = setInterval(updateTime, 1000)
})

onUnmounted(() => {
  if (timeInterval) {
    clearInterval(timeInterval)
  }
})
</script>
