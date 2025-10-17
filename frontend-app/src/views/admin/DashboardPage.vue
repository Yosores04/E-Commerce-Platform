<template>
  <AdminLayout>
    <div class="p-8">
      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow border border-neutral-200 p-6 group">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-gradient-to-br from-primary-900 to-burgundy-700 group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <span class="text-sm font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">+12.5%</span>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-1">₱{{ formatNumber(stats.totalRevenue) }}</h3>
          <p class="text-sm text-gray-600 font-medium">Total Revenue</p>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow border border-neutral-200 p-6 group">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-gradient-to-br from-primary-900 to-burgundy-700 group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
            </div>
            <span class="text-sm font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">+8.2%</span>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ formatNumber(stats.totalOrders) }}</h3>
          <p class="text-sm text-gray-600 font-medium">Total Orders</p>
        </div>

        <!-- Total Products -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow border border-neutral-200 p-6 group">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-gradient-to-br from-primary-900 to-burgundy-700 group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
            </div>
            <span class="text-sm font-semibold text-primary-900 bg-primary-50 px-3 py-1 rounded-full">+15</span>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ formatNumber(stats.totalProducts) }}</h3>
          <p class="text-sm text-gray-600 font-medium">Total Products</p>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow border border-neutral-200 p-6 group">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-gradient-to-br from-primary-900 to-burgundy-700 group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <span class="text-sm font-semibold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">+23</span>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ formatNumber(stats.totalUsers) }}</h3>
          <p class="text-sm text-gray-600 font-medium">Total Users</p>
        </div>
      </div>

      <!-- Charts and Recent Activity -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md border border-neutral-200 p-6">
          <h2 class="text-lg font-bold text-gray-900 mb-6">Revenue Overview</h2>
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
        <div class="bg-white rounded-xl shadow-md border border-neutral-200 p-6">
          <h2 class="text-lg font-bold text-gray-900 mb-6">Order Status</h2>
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
        <div class="bg-white rounded-xl shadow-md border border-neutral-200 p-6">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-900">Recent Orders</h2>
            <router-link to="/admin/orders" class="text-sm text-wine hover:text-burgundy font-semibold transition-colors">
              View All →
            </router-link>
          </div>
          <div class="space-y-3">
            <div
              v-for="order in recentOrders"
              :key="order.id"
              class="flex items-center justify-between p-4 border border-neutral-200 rounded-lg hover:border-wine hover:shadow-md transition-all cursor-pointer"
            >
              <div class="flex-1">
                <p class="font-semibold text-gray-900">{{ order.orderNumber }}</p>
                <p class="text-sm text-gray-600">{{ order.customer }}</p>
              </div>
              <div class="text-right">
                <p class="font-bold text-wine">₱{{ formatNumber(order.total) }}</p>
                <span
                  :class="getStatusClass(order.status)"
                  class="text-xs px-2.5 py-1 rounded-full font-medium"
                >
                  {{ order.status }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-xl shadow-md border border-neutral-200 p-6">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-900">Top Products</h2>
            <router-link to="/admin/products" class="text-sm text-wine hover:text-burgundy font-semibold transition-colors">
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
          class="flex items-center gap-3 p-4 bg-white rounded-xl shadow-md border border-neutral-200 hover:border-wine hover:shadow-lg transition-all group"
        >
          <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-wine to-burgundy-600 text-white group-hover:scale-110 transition-transform">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
          <span class="font-semibold text-gray-900">Products</span>
        </router-link>

        <router-link
          to="/admin/orders"
          class="flex items-center gap-3 p-4 bg-white rounded-xl shadow-md border border-neutral-200 hover:border-wine hover:shadow-lg transition-all group"
        >
          <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-wine to-burgundy-600 text-white group-hover:scale-110 transition-transform">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
          </div>
          <span class="font-semibold text-gray-900">Orders</span>
        </router-link>

        <router-link
          to="/admin/categories"
          class="flex items-center gap-3 p-4 bg-white rounded-xl shadow-md border border-neutral-200 hover:border-wine hover:shadow-lg transition-all group"
        >
          <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-wine to-burgundy-600 text-white group-hover:scale-110 transition-transform">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
          </div>
          <span class="font-semibold text-gray-900">Categories</span>
        </router-link>

        <router-link
          to="/admin/vendors"
          class="flex items-center gap-3 p-4 bg-white rounded-xl shadow-md border border-neutral-200 hover:border-wine hover:shadow-lg transition-all group"
        >
          <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-wine to-burgundy-600 text-white group-hover:scale-110 transition-transform">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <span class="font-semibold text-gray-900">Vendors</span>
        </router-link>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '../../layouts/AdminLayout.vue'
import { adminService } from '../../services/admin'

const isLoading = ref(false)

// Stats
const stats = ref({
  totalRevenue: 0,
  totalOrders: 0,
  totalProducts: 0,
  totalUsers: 0,
  orderStatus: {
    pending: 0,
    processing: 0,
    shipped: 0,
    delivered: 0,
    cancelled: 0
  }
})

// Revenue data for chart
const revenueData = ref([])

// Recent orders
const recentOrders = ref([])

// Top products
const topProducts = ref([])

const loadDashboardData = async () => {
  isLoading.value = true
  try {
    const response = await adminService.getDashboardStats()
    console.log('Dashboard API Response:', response)
    
    if (response.success && response.data) {
      const data = response.data
      console.log('Dashboard Data:', data)
      console.log('Total Products:', data.totalProducts)
      console.log('Total Users:', data.totalUsers)
      
      // Update stats
      stats.value = {
        totalRevenue: data.totalRevenue || 0,
        totalOrders: data.totalOrders || 0,
        totalProducts: data.totalProducts || 0,
        totalUsers: data.totalUsers || 0,
        orderStatus: data.orderStatus || {
          pending: 0,
          processing: 0,
          shipped: 0,
          delivered: 0,
          cancelled: 0
        }
      }
      
      // Update revenue chart data
      revenueData.value = (data.revenueByMonth || []).map(item => item.revenue)
      
      // Update recent orders
      recentOrders.value = (data.recentOrders || []).map(order => ({
        id: order.id,
        orderNumber: order.order_number,
        customer: order.customer,
        total: order.total,
        status: order.status.charAt(0).toUpperCase() + order.status.slice(1)
      }))
      
      // Update top products
      topProducts.value = (data.topProducts || []).map(product => ({
        id: product.id,
        name: product.name,
        sales: product.sales,
        revenue: product.revenue,
        image: 'https://via.placeholder.com/100/5B2333/F7F4F3?text=Product'
      }))
    }
  } catch (error) {
    console.error('Failed to load dashboard data:', error)
  } finally {
    isLoading.value = false
  }
}

onMounted(async () => {
  await loadDashboardData()
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
