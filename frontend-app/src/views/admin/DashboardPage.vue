<template>
  <div class="min-h-screen bg-white-smoke">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-xerxia-wine">Admin Dashboard</h1>
            <p class="text-gray-600 mt-1">Welcome back, Admin</p>
          </div>
          <div class="text-right">
            <p class="text-sm text-gray-600">{{ currentDate }}</p>
            <p class="text-sm text-gray-600">{{ currentTime }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-green-100">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <span class="text-sm font-medium text-green-600">+12.5%</span>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-1">₱{{ formatNumber(stats.totalRevenue) }}</h3>
          <p class="text-sm text-gray-600">Total Revenue</p>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-blue-100">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
            </div>
            <span class="text-sm font-medium text-blue-600">+8.2%</span>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ formatNumber(stats.totalOrders) }}</h3>
          <p class="text-sm text-gray-600">Total Orders</p>
        </div>

        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-purple-100">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
            </div>
            <span class="text-sm font-medium text-purple-600">+15</span>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ formatNumber(stats.totalProducts) }}</h3>
          <p class="text-sm text-gray-600">Total Products</p>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-yellow-100">
              <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <span class="text-sm font-medium text-yellow-600">+23</span>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ formatNumber(stats.totalUsers) }}</h3>
          <p class="text-sm text-gray-600">Total Users</p>
        </div>
      </div>

      <!-- Charts and Recent Activity -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Revenue Overview</h2>
          <div class="h-64 flex items-end justify-between gap-2">
            <div
              v-for="(value, index) in revenueData"
              :key="index"
              class="flex-1 bg-gradient-to-t from-xerxia-wine to-burgundy rounded-t-lg transition-all hover:opacity-80 cursor-pointer relative group"
              :style="{ height: `${(value / Math.max(...revenueData)) * 100}%` }"
            >
              <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-xs px-2 py-1 rounded whitespace-nowrap">
                ₱{{ formatNumber(value) }}
              </div>
            </div>
          </div>
          <div class="flex justify-between mt-4 text-xs text-gray-600">
            <span>Jan</span>
            <span>Feb</span>
            <span>Mar</span>
            <span>Apr</span>
            <span>May</span>
            <span>Jun</span>
            <span>Jul</span>
            <span>Aug</span>
            <span>Sep</span>
            <span>Oct</span>
            <span>Nov</span>
            <span>Dec</span>
          </div>
        </div>

        <!-- Order Status -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Order Status</h2>
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                <span class="text-sm text-gray-600">Pending</span>
              </div>
              <span class="text-sm font-semibold text-gray-900">{{ stats.orderStatus.pending }}</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                <span class="text-sm text-gray-600">Processing</span>
              </div>
              <span class="text-sm font-semibold text-gray-900">{{ stats.orderStatus.processing }}</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-purple-500"></div>
                <span class="text-sm text-gray-600">Shipped</span>
              </div>
              <span class="text-sm font-semibold text-gray-900">{{ stats.orderStatus.shipped }}</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                <span class="text-sm text-gray-600">Delivered</span>
              </div>
              <span class="text-sm font-semibold text-gray-900">{{ stats.orderStatus.delivered }}</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <span class="text-sm text-gray-600">Cancelled</span>
              </div>
              <span class="text-sm font-semibold text-gray-900">{{ stats.orderStatus.cancelled }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Orders and Top Products -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Recent Orders</h2>
            <router-link to="/admin/orders" class="text-sm text-xerxia-wine hover:text-burgundy font-medium">
              View All →
            </router-link>
          </div>
          <div class="space-y-4">
            <div
              v-for="order in recentOrders"
              :key="order.id"
              class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-xerxia-wine transition-colors cursor-pointer"
            >
              <div class="flex-1">
                <p class="font-semibold text-gray-900">{{ order.orderNumber }}</p>
                <p class="text-sm text-gray-600">{{ order.customer }}</p>
              </div>
              <div class="text-right">
                <p class="font-semibold text-xerxia-wine">₱{{ formatNumber(order.total) }}</p>
                <span
                  :class="getStatusClass(order.status)"
                  class="text-xs px-2 py-1 rounded-full"
                >
                  {{ order.status }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Top Products</h2>
            <router-link to="/admin/products" class="text-sm text-xerxia-wine hover:text-burgundy font-medium">
              View All →
            </router-link>
          </div>
          <div class="space-y-4">
            <div
              v-for="product in topProducts"
              :key="product.id"
              class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:border-xerxia-wine transition-colors cursor-pointer"
            >
              <img
                :src="product.image"
                :alt="product.name"
                class="w-12 h-12 rounded-lg object-cover"
              />
              <div class="flex-1">
                <p class="font-semibold text-gray-900">{{ product.name }}</p>
                <p class="text-sm text-gray-600">{{ product.sales }} sold</p>
              </div>
              <div class="text-right">
                <p class="font-semibold text-xerxia-wine">₱{{ formatNumber(product.revenue) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
        <router-link
          to="/admin/products"
          class="flex items-center gap-3 p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:border-xerxia-wine transition-colors"
        >
          <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-xerxia-wine text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
          <span class="font-medium text-gray-900">Products</span>
        </router-link>

        <router-link
          to="/admin/orders"
          class="flex items-center gap-3 p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:border-xerxia-wine transition-colors"
        >
          <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-xerxia-wine text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
          </div>
          <span class="font-medium text-gray-900">Orders</span>
        </router-link>

        <router-link
          to="/admin/categories"
          class="flex items-center gap-3 p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:border-xerxia-wine transition-colors"
        >
          <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-xerxia-wine text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
          </div>
          <span class="font-medium text-gray-900">Categories</span>
        </router-link>

        <router-link
          to="/admin/vendors"
          class="flex items-center gap-3 p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:border-xerxia-wine transition-colors"
        >
          <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-xerxia-wine text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <span class="font-medium text-gray-900">Vendors</span>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

// Current date and time
const currentDate = ref('')
const currentTime = ref('')

// Stats
const stats = ref({
  totalRevenue: 1250000,
  totalOrders: 3847,
  totalProducts: 256,
  totalUsers: 1523,
  orderStatus: {
    pending: 45,
    processing: 82,
    shipped: 134,
    delivered: 2891,
    cancelled: 95
  }
})

// Revenue data for chart
const revenueData = ref([
  85000, 92000, 78000, 105000, 98000, 112000, 
  125000, 118000, 135000, 142000, 156000, 168000
])

// Recent orders
const recentOrders = ref([
  { id: 1, orderNumber: 'ORD-2024-156', customer: 'Juan Dela Cruz', total: 5971, status: 'Processing' },
  { id: 2, orderNumber: 'ORD-2024-155', customer: 'Maria Santos', total: 3899, status: 'Shipped' },
  { id: 3, orderNumber: 'ORD-2024-154', customer: 'Pedro Reyes', total: 8499, status: 'Delivered' },
  { id: 4, orderNumber: 'ORD-2024-153', customer: 'Ana Garcia', total: 2599, status: 'Pending' },
  { id: 5, orderNumber: 'ORD-2024-152', customer: 'Carlos Lopez', total: 4299, status: 'Processing' }
])

// Top products
const topProducts = ref([
  { id: 1, name: 'Merlot Reserve 2020', sales: 234, revenue: 373866, image: 'https://picsum.photos/seed/wine1/100/100' },
  { id: 2, name: 'Cabernet Sauvignon', sales: 198, revenue: 257202, image: 'https://picsum.photos/seed/wine2/100/100' },
  { id: 3, name: 'Chardonnay Classic', sales: 187, revenue: 186813, image: 'https://picsum.photos/seed/wine3/100/100' },
  { id: 4, name: 'Pinot Noir Vintage', sales: 156, revenue: 280644, image: 'https://picsum.photos/seed/wine4/100/100' },
  { id: 5, name: 'Rosé Wine Special', sales: 143, revenue: 185857, image: 'https://picsum.photos/seed/wine5/100/100' }
])

// Update time
let timeInterval = null

const updateTime = () => {
  const now = new Date()
  currentDate.value = now.toLocaleDateString('en-PH', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
  currentTime.value = now.toLocaleTimeString('en-PH', {
    hour: '2-digit',
    minute: '2-digit'
  })
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

const formatNumber = (num) => {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
}

const getStatusClass = (status) => {
  const classes = {
    'Pending': 'bg-yellow-100 text-yellow-800',
    'Processing': 'bg-blue-100 text-blue-800',
    'Shipped': 'bg-purple-100 text-purple-800',
    'Delivered': 'bg-green-100 text-green-800',
    'Cancelled': 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}
</script>
