<template>
  <!-- Xerxia Header with Wine & White Smoke Theme - Shrinks on Scroll -->
  <header 
    :class="[
      'sticky top-0 z-50 backdrop-blur-xl bg-white/90 border-b border-neutral-200/50 shadow-soft transition-all duration-300',
      scrolled ? 'header-shrunk' : ''
    ]"
  >
    <!-- Top bar with elegant Wine gradient - Hidden when scrolled -->
    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="transform -translate-y-full opacity-0"
      enter-to-class="transform translate-y-0 opacity-100"
      leave-active-class="transition duration-300 ease-in"
      leave-from-class="transform translate-y-0 opacity-100"
      leave-to-class="transform -translate-y-full opacity-0"
    >
      <div v-show="!scrolled" class="bg-gradient-to-r from-primary-900 via-primary-800 to-burgundy-900 text-neutral-50">
        <div class="container mx-auto px-4 py-2.5">
          <div class="flex justify-between items-center text-sm font-medium">
            <div class="flex items-center space-x-6">
              <span class="flex items-center space-x-2">
                <SparklesIcon class="w-4 h-4 text-gold-400" />
                <span>Welcome to Xerxia Marketplace</span>
              </span>
              <span class="hidden md:flex items-center space-x-1 text-neutral-100">
                <TruckIcon class="w-4 h-4" />
                <span>Free shipping on orders over ₱2,500</span>
              </span>
            </div>
            <div class="flex items-center space-x-4">
              <template v-if="authStore.isAuthenticated">
                <Menu as="div" class="relative">
                  <MenuButton class="flex items-center space-x-2 hover:text-gold-300 transition-colors">
                    <UserCircleIcon class="w-5 h-5" />
                    <span>{{ authStore.user?.name }}</span>
                    <ChevronDownIcon class="w-4 h-4" />
                  </MenuButton>
                  <transition
                    enter-active-class="transition duration-200 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-150 ease-in"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                  >
                    <MenuItems class="absolute right-0 mt-2 w-56 origin-top-right rounded-xl bg-white shadow-soft-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden">
                      <div class="p-2">
                        <MenuItem v-if="authStore.hasRole('vendor')" v-slot="{ active }">
                          <router-link
                            to="/vendor/dashboard"
                            :class="[
                              active ? 'bg-primary-50 text-primary-900' : 'text-neutral-700',
                              'group flex items-center w-full px-3 py-2 text-sm rounded-lg transition-colors'
                            ]"
                          >
                            <BuildingStorefrontIcon class="w-5 h-5 mr-3" />
                            Vendor Dashboard
                          </router-link>
                        </MenuItem>
                        <MenuItem v-if="authStore.hasRole('admin')" v-slot="{ active }">
                          <router-link
                            to="/admin/dashboard"
                            :class="[
                              active ? 'bg-primary-50 text-primary-900' : 'text-neutral-700',
                              'group flex items-center w-full px-3 py-2 text-sm rounded-lg transition-colors'
                            ]"
                          >
                            <ShieldCheckIcon class="w-5 h-5 mr-3" />
                            Admin Panel
                          </router-link>
                        </MenuItem>
                        <MenuItem v-slot="{ active }">
                          <router-link
                            to="/profile"
                            :class="[
                              active ? 'bg-primary-50 text-primary-900' : 'text-neutral-700',
                              'group flex items-center w-full px-3 py-2 text-sm rounded-lg transition-colors'
                            ]"
                          >
                            <UserIcon class="w-5 h-5 mr-3" />
                            My Profile
                          </router-link>
                        </MenuItem>
                        <div class="my-1 h-px bg-neutral-200"></div>
                        <MenuItem v-slot="{ active }">
                          <button
                            @click="handleLogout"
                            :class="[
                              active ? 'bg-red-50 text-red-700' : 'text-neutral-700',
                              'group flex items-center w-full px-3 py-2 text-sm rounded-lg transition-colors'
                            ]"
                          >
                            <ArrowRightOnRectangleIcon class="w-5 h-5 mr-3" />
                            Logout
                          </button>
                        </MenuItem>
                      </div>
                    </MenuItems>
                  </transition>
                </Menu>
              </template>
              <template v-else>
                <router-link to="/login" class="hover:text-gold-300 transition-colors flex items-center space-x-1">
                  <ArrowRightOnRectangleIcon class="w-4 h-4" />
                  <span>Login</span>
                </router-link>
                <router-link 
                  to="/register" 
                  class="bg-gold-500/20 hover:bg-gold-500/30 px-4 py-1.5 rounded-lg transition-all backdrop-blur-sm border border-gold-500/30"
                >
                  Register
                </router-link>
              </template>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <!-- Main header - Compact when scrolled -->
    <div 
      :class="[
        'container mx-auto px-4 transition-all duration-300',
        scrolled ? 'py-3' : 'py-5'
      ]"
    >
      <div class="flex items-center justify-between gap-8">
        <!-- Xerxia Logo with elegant Wine theme -->
        <router-link to="/" class="flex items-center space-x-3 group">
          <div class="relative">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900 to-burgundy-900 rounded-2xl blur-sm opacity-50 group-hover:opacity-75 transition-opacity"></div>
            <div class="relative w-12 h-12 bg-gradient-to-br from-primary-900 to-burgundy-900 rounded-2xl flex items-center justify-center shadow-glow">
              <span class="text-gold-400 font-bold text-2xl font-display">X</span>
            </div>
          </div>
          <div>
            <span class="text-2xl font-bold font-display bg-gradient-to-r from-primary-900 via-burgundy-900 to-primary-800 bg-clip-text text-transparent">
              Xerxia
            </span>
            <p class="text-xs text-neutral-500 -mt-1">Elegance in every purchase</p>
          </div>
        </router-link>

        <!-- Elegant Search bar with Wine theme -->
        <div class="flex-1 max-w-2xl">
          <form @submit.prevent="handleSearch" class="relative group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary-900 to-burgundy-900 rounded-2xl blur opacity-0 group-hover:opacity-20 transition-opacity"></div>
            <div class="relative flex items-center">
              <MagnifyingGlassIcon class="absolute left-4 w-5 h-5 text-neutral-400" />
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search for products, brands, and more..."
                class="w-full pl-12 pr-32 py-3.5 bg-neutral-50 border border-neutral-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-900 focus:border-transparent transition-all placeholder:text-neutral-400"
              />
              <button
                type="submit"
                class="absolute right-2 bg-gradient-to-r from-primary-900 to-burgundy-900 text-white px-6 py-2.5 rounded-xl hover:shadow-glow transition-all duration-300 font-medium flex items-center space-x-2"
              >
                <span>Search</span>
                <ArrowRightIcon class="w-4 h-4" />
              </button>
            </div>
          </form>
        </div>

        <!-- Cart & User Actions with Wine theme -->
        <div class="flex items-center space-x-4">
          <!-- Orders -->
          <router-link 
            v-if="authStore.isAuthenticated"
            to="/orders" 
            class="flex flex-col items-center p-2 rounded-xl hover:bg-primary-50 transition-colors group"
          >
            <div class="relative">
              <DocumentTextIcon class="w-6 h-6 text-neutral-600 group-hover:text-primary-900 transition-colors" />
            </div>
            <span class="text-xs text-neutral-600 group-hover:text-primary-900 transition-colors mt-1">Orders</span>
          </router-link>

          <!-- Cart with animated badge -->
          <router-link 
            to="/cart" 
            class="relative flex flex-col items-center p-2 rounded-xl hover:bg-primary-50 transition-colors group"
          >
            <div class="relative">
              <ShoppingCartIcon class="w-6 h-6 text-neutral-600 group-hover:text-primary-900 transition-colors" />
              <transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="transform scale-0"
                enter-to-class="transform scale-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="transform scale-100"
                leave-to-class="transform scale-0"
              >
                <span 
                  v-if="cartStore.itemCount > 0"
                  class="absolute -top-2 -right-2 bg-gradient-to-r from-burgundy-600 to-primary-900 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center shadow-lg animate-scale-in"
                >
                  {{ cartStore.itemCount }}
                </span>
              </transition>
            </div>
            <span class="text-xs text-neutral-600 group-hover:text-primary-900 transition-colors mt-1">Cart</span>
          </router-link>
        </div>
      </div>

      <!-- Elegant Navigation -->
      <nav class="mt-6 pt-4 border-t border-neutral-200/50">
        <ul class="flex items-center space-x-1">
          <li>
            <router-link 
              to="/" 
              class="px-4 py-2 rounded-lg text-neutral-700 hover:bg-primary-50 hover:text-primary-900 transition-all font-medium flex items-center space-x-2"
            >
              <HomeIcon class="w-4 h-4" />
              <span>Home</span>
            </router-link>
          </li>
          <li>
            <router-link 
              to="/products" 
              class="px-4 py-2 rounded-lg text-neutral-700 hover:bg-primary-50 hover:text-primary-900 transition-all font-medium"
            >
              All Products
            </router-link>
          </li>
          
          <!-- Categories Dropdown -->
          <li>
            <Menu as="div" class="relative">
              <MenuButton class="px-4 py-2 rounded-lg text-neutral-700 hover:bg-primary-50 hover:text-primary-900 transition-all font-medium flex items-center space-x-1">
                <span>Categories</span>
                <ChevronDownIcon class="w-4 h-4" />
              </MenuButton>
              <transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
              >
                <MenuItems class="absolute left-0 mt-2 w-64 origin-top-left rounded-xl bg-white shadow-soft-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden">
                  <div class="p-2">
                    <MenuItem v-slot="{ active }">
                      <a
                        href="#"
                        :class="[
                          active ? 'bg-primary-50 text-primary-900' : 'text-neutral-700',
                          'block px-4 py-3 text-sm rounded-lg transition-colors'
                        ]"
                      >
                        Electronics
                      </a>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                      <a
                        href="#"
                        :class="[
                          active ? 'bg-primary-50 text-primary-900' : 'text-neutral-700',
                          'block px-4 py-3 text-sm rounded-lg transition-colors'
                        ]"
                      >
                        Fashion
                      </a>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                      <a
                        href="#"
                        :class="[
                          active ? 'bg-primary-50 text-primary-900' : 'text-neutral-700',
                          'block px-4 py-3 text-sm rounded-lg transition-colors'
                        ]"
                      >
                        Home & Living
                      </a>
                    </MenuItem>
                  </div>
                </MenuItems>
              </transition>
            </Menu>
          </li>
          
          <li>
            <a href="#" class="px-4 py-2 rounded-lg text-neutral-700 hover:bg-primary-50 hover:text-primary-900 transition-all font-medium flex items-center space-x-2">
              <SparklesIcon class="w-4 h-4 text-gold-500" />
              <span>New Arrivals</span>
            </a>
          </li>
          <li>
            <a href="#" class="px-4 py-2 rounded-lg text-neutral-700 hover:bg-primary-50 hover:text-primary-900 transition-all font-medium flex items-center space-x-2">
              <FireIcon class="w-4 h-4 text-burgundy-600" />
              <span>Best Sellers</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import {
  MagnifyingGlassIcon,
  ShoppingCartIcon,
  ShoppingBagIcon,
  UserCircleIcon,
  UserIcon,
  HomeIcon,
  SparklesIcon,
  FireIcon,
  TruckIcon,
  DocumentTextIcon,
  ChevronDownIcon,
  ArrowRightIcon,
  ArrowRightOnRectangleIcon,
  BuildingStorefrontIcon,
  ShieldCheckIcon,
} from '@heroicons/vue/24/outline'
import { useAuthStore } from '../stores/auth'
import { useCartStore } from '../stores/cart'

const router = useRouter()
const authStore = useAuthStore()
const cartStore = useCartStore()
const searchQuery = ref('')
const scrolled = ref(false)

// Handle scroll event
const handleScroll = () => {
  scrolled.value = window.scrollY > 50
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})

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

<style scoped>
/* Smooth header shrinking animation */
.header-shrunk {
  box-shadow: 0 4px 20px rgba(91, 35, 51, 0.08);
}

/* Smooth scale animation for cart badge */
@keyframes scale-in {
  0% {
    transform: scale(0);
  }
  50% {
    transform: scale(1.2);
  }
  100% {
    transform: scale(1);
  }
}

.animate-scale-in {
  animation: scale-in 0.3s ease-out;
}

/* Logo glow effect */
.shadow-glow {
  box-shadow: 0 4px 20px rgba(91, 35, 51, 0.3);
}

.shadow-glow:hover {
  box-shadow: 0 6px 30px rgba(91, 35, 51, 0.5);
}

/* Smooth soft shadow */
.shadow-soft {
  box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
}

.shadow-soft-lg {
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}
</style>
