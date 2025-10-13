<template>
  <div class="min-h-screen bg-white-smoke">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-xerxia-wine">Reports & Analytics</h1>
            <p class="text-gray-600 mt-1">View detailed insights and export reports</p>
          </div>
          <div class="flex gap-3">
            <button class="px-6 py-2 border-2 border-xerxia-wine text-xerxia-wine rounded-lg hover:bg-xerxia-wine hover:text-white transition-colors font-medium flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Export PDF
            </button>
            <button class="px-6 py-2 bg-xerxia-wine text-white rounded-lg hover:bg-burgundy transition-colors font-medium flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Export Excel
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Date Range Filter -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex flex-wrap items-end gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
            <input
              type="date"
              v-model="dateFrom"
              class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
            <input
              type="date"
              v-model="dateTo"
              class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
            />
          </div>
          <div class="flex gap-2">
            <button
              @click="setQuickRange('today')"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium"
            >
              Today
            </button>
            <button
              @click="setQuickRange('week')"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium"
            >
              This Week
            </button>
            <button
              @click="setQuickRange('month')"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium"
            >
              This Month
            </button>
            <button
              @click="setQuickRange('year')"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium"
            >
              This Year
            </button>
          </div>
        </div>
      </div>

      <!-- Key Metrics -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-gray-600">Total Revenue</h3>
            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
          <p class="text-3xl font-bold text-gray-900 mb-2">₱{{ formatNumber(metrics.totalRevenue) }}</p>
          <div class="flex items-center text-sm">
            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
            <span class="text-green-600 font-medium">+12.5%</span>
            <span class="text-gray-500 ml-1">vs last period</span>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-gray-600">Total Orders</h3>
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
            </div>
          </div>
          <p class="text-3xl font-bold text-gray-900 mb-2">{{ formatNumber(metrics.totalOrders) }}</p>
          <div class="flex items-center text-sm">
            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
            <span class="text-green-600 font-medium">+8.2%</span>
            <span class="text-gray-500 ml-1">vs last period</span>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-gray-600">Avg Order Value</h3>
            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
              </svg>
            </div>
          </div>
          <p class="text-3xl font-bold text-gray-900 mb-2">₱{{ formatNumber(metrics.avgOrderValue) }}</p>
          <div class="flex items-center text-sm">
            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
            <span class="text-green-600 font-medium">+5.3%</span>
            <span class="text-gray-500 ml-1">vs last period</span>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-gray-600">Conversion Rate</h3>
            <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
          </div>
          <p class="text-3xl font-bold text-gray-900 mb-2">{{ metrics.conversionRate }}%</p>
          <div class="flex items-center text-sm">
            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
            <span class="text-green-600 font-medium">+2.1%</span>
            <span class="text-gray-500 ml-1">vs last period</span>
          </div>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Trend -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Revenue Trend</h3>
          <div class="h-80 flex items-end justify-between gap-2">
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
            <span v-for="month in months" :key="month">{{ month }}</span>
          </div>
        </div>

        <!-- Top Categories -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Top Categories</h3>
          <div class="space-y-4">
            <div v-for="category in topCategories" :key="category.name">
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-900">{{ category.name }}</span>
                <span class="text-sm font-semibold text-xerxia-wine">₱{{ formatNumber(category.revenue) }}</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div
                  class="bg-gradient-to-r from-xerxia-wine to-burgundy h-2 rounded-full transition-all"
                  :style="{ width: `${category.percentage}%` }"
                ></div>
              </div>
              <p class="text-xs text-gray-500 mt-1">{{ category.orders }} orders ({{ category.percentage }}%)</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Products & Recent Transactions -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Top Selling Products</h3>
          <div class="space-y-4">
            <div
              v-for="(product, index) in topProducts"
              :key="product.id"
              class="flex items-center gap-4 p-3 border border-gray-200 rounded-lg hover:border-xerxia-wine transition-colors"
            >
              <div class="flex-shrink-0 w-8 h-8 rounded-full bg-xerxia-wine text-white flex items-center justify-center font-bold text-sm">
                {{ index + 1 }}
              </div>
              <img
                :src="product.image"
                :alt="product.name"
                class="w-12 h-12 rounded-lg object-cover"
              />
              <div class="flex-1">
                <p class="font-medium text-gray-900">{{ product.name }}</p>
                <p class="text-sm text-gray-500">{{ product.sales }} sold</p>
              </div>
              <div class="text-right">
                <p class="font-semibold text-xerxia-wine">₱{{ formatNumber(product.revenue) }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Sales by Payment Method -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Sales by Payment Method</h3>
          <div class="space-y-6">
            <div v-for="method in paymentMethods" :key="method.name">
              <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                  <div :class="`w-4 h-4 rounded ${method.color}`"></div>
                  <span class="text-sm font-medium text-gray-900">{{ method.name }}</span>
                </div>
                <span class="text-sm font-semibold text-gray-900">{{ method.percentage }}%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div
                  :class="`h-2 rounded-full transition-all ${method.color}`"
                  :style="{ width: `${method.percentage}%` }"
                ></div>
              </div>
              <div class="flex justify-between mt-1">
                <p class="text-xs text-gray-500">{{ method.transactions }} transactions</p>
                <p class="text-xs font-medium text-gray-700">₱{{ formatNumber(method.amount) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

// Date filters
const dateFrom = ref('2024-01-01')
const dateTo = ref('2024-10-13')

// Metrics
const metrics = ref({
  totalRevenue: 1250000,
  totalOrders: 3847,
  avgOrderValue: 3249,
  conversionRate: 3.8
})

// Revenue data
const revenueData = ref([
  85000, 92000, 78000, 105000, 98000, 112000, 
  125000, 118000, 135000, 142000, 156000, 168000
])

const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

// Top categories
const topCategories = ref([
  { name: 'Red Wines', revenue: 450000, orders: 1234, percentage: 85 },
  { name: 'White Wines', revenue: 380000, orders: 987, percentage: 72 },
  { name: 'Sparkling Wines', revenue: 220000, orders: 654, percentage: 55 },
  { name: 'Rosé Wines', revenue: 200000, orders: 543, percentage: 48 }
])

// Top products
const topProducts = ref([
  { id: 1, name: 'Merlot Reserve 2020', sales: 234, revenue: 373866, image: 'https://picsum.photos/seed/wine1/100/100' },
  { id: 2, name: 'Cabernet Sauvignon', sales: 198, revenue: 257202, image: 'https://picsum.photos/seed/wine2/100/100' },
  { id: 3, name: 'Chardonnay Classic', sales: 187, revenue: 186813, image: 'https://picsum.photos/seed/wine3/100/100' },
  { id: 4, name: 'Pinot Noir Vintage', sales: 156, revenue: 280644, image: 'https://picsum.photos/seed/wine4/100/100' },
  { id: 5, name: 'Rosé Wine Special', sales: 143, revenue: 185857, image: 'https://picsum.photos/seed/wine5/100/100' }
])

// Payment methods
const paymentMethods = ref([
  { name: 'Credit/Debit Card', percentage: 45, transactions: 1730, amount: 562500, color: 'bg-blue-500' },
  { name: 'GCash', percentage: 30, transactions: 1154, amount: 375000, color: 'bg-green-500' },
  { name: 'Cash on Delivery', percentage: 15, transactions: 577, amount: 187500, color: 'bg-yellow-500' },
  { name: 'Bank Transfer', percentage: 10, transactions: 385, amount: 125000, color: 'bg-purple-500' }
])

const setQuickRange = (range) => {
  const today = new Date()
  const year = today.getFullYear()
  const month = String(today.getMonth() + 1).padStart(2, '0')
  const day = String(today.getDate()).padStart(2, '0')
  
  dateTo.value = `${year}-${month}-${day}`
  
  switch(range) {
    case 'today':
      dateFrom.value = `${year}-${month}-${day}`
      break
    case 'week':
      const weekAgo = new Date(today)
      weekAgo.setDate(today.getDate() - 7)
      dateFrom.value = weekAgo.toISOString().split('T')[0]
      break
    case 'month':
      dateFrom.value = `${year}-${month}-01`
      break
    case 'year':
      dateFrom.value = `${year}-01-01`
      break
  }
}

const formatNumber = (num) => {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
}
</script>
