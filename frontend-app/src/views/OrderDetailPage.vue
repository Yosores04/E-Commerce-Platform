<template>
  <div class="min-h-screen bg-white-smoke py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
        <router-link to="/orders" class="hover:text-xerxia-wine">My Orders</router-link>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-900 font-medium">Order {{ order.orderNumber }}</span>
      </nav>

      <!-- Header Section -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <div class="flex items-center gap-3 mb-3">
              <h1 class="text-2xl font-bold text-gray-900">Order {{ order.orderNumber }}</h1>
              <span
                :class="getStatusClass(order.status)"
                class="px-3 py-1 rounded-full text-sm font-semibold"
              >
                {{ order.status.charAt(0).toUpperCase() + order.status.slice(1) }}
              </span>
            </div>
            <div class="space-y-1 text-sm text-gray-600">
              <p>
                <span class="font-medium">Order Date:</span>
                {{ formatDate(order.date) }}
              </p>
              <p>
                <span class="font-medium">Expected Delivery:</span>
                {{ formatDate(order.expectedDelivery) }}
              </p>
            </div>
          </div>
          <div class="text-right">
            <p class="text-sm text-gray-600 mb-1">Total Amount</p>
            <p class="text-3xl font-bold text-xerxia-wine">{{ formatPrice(order.total) }}</p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Order Status Timeline -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Order Status</h2>
            <div class="relative">
              <!-- Timeline Line -->
              <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200" />

              <!-- Timeline Items -->
              <div
                v-for="(status, index) in orderStatusTimeline"
                :key="index"
                class="relative flex gap-4 pb-8 last:pb-0"
              >
                <!-- Icon -->
                <div
                  :class="[
                    'relative z-10 flex items-center justify-center w-8 h-8 rounded-full border-2',
                    status.completed
                      ? 'bg-green-500 border-green-500'
                      : status.current
                      ? 'bg-blue-500 border-blue-500'
                      : 'bg-white border-gray-300'
                  ]"
                >
                  <svg
                    v-if="status.completed"
                    class="w-4 h-4 text-white"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                      clip-rule="evenodd"
                    />
                  </svg>
                  <div
                    v-else-if="status.current"
                    class="w-3 h-3 bg-white rounded-full animate-pulse"
                  />
                </div>

                <!-- Content -->
                <div class="flex-1 pt-0.5">
                  <h3
                    :class="[
                      'font-semibold mb-1',
                      status.completed || status.current
                        ? 'text-gray-900'
                        : 'text-gray-500'
                    ]"
                  >
                    {{ status.title }}
                  </h3>
                  <p class="text-sm text-gray-600">{{ status.description }}</p>
                  <p
                    v-if="status.date"
                    class="text-xs text-gray-500 mt-1"
                  >
                    {{ formatDateTime(status.date) }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Order Items -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Order Items ({{ order.items.length }})</h2>
            <div class="space-y-4">
              <div
                v-for="item in order.items"
                :key="item.id"
                class="flex gap-4 p-4 border border-gray-200 rounded-lg hover:border-xerxia-wine transition-colors"
              >
                <img
                  :src="item.image"
                  :alt="item.name"
                  class="w-20 h-20 rounded-lg object-cover"
                />
                <div class="flex-1">
                  <h3 class="font-semibold text-gray-900 mb-1">{{ item.name }}</h3>
                  <p class="text-sm text-gray-600 mb-2">{{ item.description }}</p>
                  <p class="text-sm text-gray-600">
                    Quantity: <span class="font-medium">{{ item.quantity }}</span>
                  </p>
                </div>
                <div class="text-right">
                  <p class="text-lg font-bold text-xerxia-wine">
                    {{ formatPrice(item.price * item.quantity) }}
                  </p>
                  <p class="text-sm text-gray-600">
                    {{ formatPrice(item.price) }} each
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Order Summary -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
            <div class="space-y-3 text-sm">
              <div class="flex justify-between text-gray-600">
                <span>Subtotal</span>
                <span>{{ formatPrice(order.subtotal) }}</span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Shipping</span>
                <span>{{ formatPrice(order.shipping) }}</span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Tax (12%)</span>
                <span>{{ formatPrice(order.tax) }}</span>
              </div>
              <div class="border-t border-gray-200 pt-3 flex justify-between font-bold text-lg">
                <span>Total</span>
                <span class="text-xerxia-wine">{{ formatPrice(order.total) }}</span>
              </div>
            </div>
          </div>

          <!-- Shipping Address -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-2 mb-4">
              <svg class="w-5 h-5 text-xerxia-wine" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <h2 class="text-lg font-semibold text-gray-900">Shipping Address</h2>
            </div>
            <div class="text-sm text-gray-600 space-y-1">
              <p class="font-medium text-gray-900">{{ order.shippingAddress.name }}</p>
              <p>{{ order.shippingAddress.street }}</p>
              <p>{{ order.shippingAddress.city }}, {{ order.shippingAddress.province }}</p>
              <p>{{ order.shippingAddress.postalCode }}</p>
              <p class="pt-2">{{ order.shippingAddress.phone }}</p>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-2 mb-4">
              <svg class="w-5 h-5 text-xerxia-wine" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
              <h2 class="text-lg font-semibold text-gray-900">Payment Method</h2>
            </div>
            <div class="text-sm text-gray-600">
              <p class="font-medium text-gray-900 mb-1">{{ order.paymentMethod }}</p>
              <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                Payment Confirmed
              </div>
            </div>
          </div>

          <!-- Tracking Info -->
          <div
            v-if="order.trackingNumber"
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
          >
            <div class="flex items-center gap-2 mb-4">
              <svg class="w-5 h-5 text-xerxia-wine" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
              </svg>
              <h2 class="text-lg font-semibold text-gray-900">Tracking Info</h2>
            </div>
            <div class="text-sm">
              <p class="text-gray-600 mb-2">Tracking Number</p>
              <p class="font-mono font-medium text-gray-900 bg-gray-50 px-3 py-2 rounded">
                {{ order.trackingNumber }}
              </p>
              <button class="mt-3 w-full px-4 py-2 border-2 border-xerxia-wine text-xerxia-wine rounded-lg hover:bg-xerxia-wine hover:text-white transition-colors font-medium">
                Track Package
              </button>
            </div>
          </div>

          <!-- Order Actions -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
            <div class="space-y-3">
              <button
                v-if="order.status === 'pending' || order.status === 'processing'"
                @click="showCancelModal = true"
                class="w-full px-4 py-2 border-2 border-red-500 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-colors font-medium"
              >
                Cancel Order
              </button>
              <button
                v-if="order.status === 'delivered'"
                class="w-full px-4 py-2 border-2 border-xerxia-wine text-xerxia-wine rounded-lg hover:bg-xerxia-wine hover:text-white transition-colors font-medium"
              >
                Request Return
              </button>
              <button class="w-full px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Download Invoice
              </button>
              <router-link
                to="/support"
                class="block w-full px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-center"
              >
                Contact Support
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Cancel Confirmation Modal -->
      <div
        v-if="showCancelModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        @click.self="showCancelModal = false"
      >
        <div class="bg-white rounded-lg max-w-md w-full p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Cancel Order?</h3>
          <p class="text-gray-600 mb-6">
            Are you sure you want to cancel order {{ order.orderNumber }}? This action cannot be undone.
          </p>
          <div class="flex gap-3">
            <button
              @click="showCancelModal = false"
              class="flex-1 px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
            >
              Keep Order
            </button>
            <button
              @click="cancelOrder"
              class="flex-1 px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium"
            >
              Yes, Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

// Cancel modal
const showCancelModal = ref(false)

// Mock order data (in real app, fetch from API using route.params.id)
const order = ref({
  id: route.params.id,
  orderNumber: 'ORD-2024-003',
  date: '2024-11-14',
  expectedDelivery: '2024-11-20',
  status: 'processing',
  subtotal: 7999,
  shipping: 350,
  tax: 960,
  total: 9309,
  trackingNumber: 'XRX-PH-2024-1114-003',
  shippingAddress: {
    name: 'Juan Dela Cruz',
    street: '123 Makati Avenue',
    city: 'Makati',
    province: 'Metro Manila',
    postalCode: '1200',
    phone: '+63 912 345 6789'
  },
  paymentMethod: 'Credit Card ending in 4242',
  items: [
    {
      id: 1,
      name: 'Merlot Reserve 2020',
      description: 'Premium Red Wine - 750ml',
      price: 1599,
      quantity: 2,
      image: 'https://picsum.photos/seed/wine6/200/200'
    },
    {
      id: 2,
      name: 'Cabernet Sauvignon',
      description: 'Classic Red Wine - 750ml',
      price: 1299,
      quantity: 1,
      image: 'https://picsum.photos/seed/wine7/200/200'
    },
    {
      id: 3,
      name: 'Pinot Noir Vintage',
      description: 'Elegant Red Wine - 750ml',
      price: 1799,
      quantity: 1,
      image: 'https://picsum.photos/seed/wine8/200/200'
    },
    {
      id: 4,
      name: 'Chardonnay Classic',
      description: 'Refreshing White Wine - 750ml',
      price: 999,
      quantity: 1,
      image: 'https://picsum.photos/seed/wine9/200/200'
    }
  ]
})

// Order status timeline
const orderStatusTimeline = computed(() => {
  const statuses = [
    {
      title: 'Order Placed',
      description: 'Your order has been received',
      date: '2024-11-14T10:30:00',
      completed: true,
      current: false
    },
    {
      title: 'Payment Confirmed',
      description: 'Payment has been verified',
      date: '2024-11-14T10:31:00',
      completed: true,
      current: false
    },
    {
      title: 'Processing',
      description: 'Your order is being prepared',
      date: '2024-11-14T14:00:00',
      completed: true,
      current: true
    },
    {
      title: 'Shipped',
      description: 'Your order is on the way',
      date: null,
      completed: false,
      current: false
    },
    {
      title: 'Delivered',
      description: 'Your order has been delivered',
      date: null,
      completed: false,
      current: false
    }
  ]

  // Adjust based on actual order status
  const statusOrder = ['pending', 'processing', 'shipped', 'delivered', 'cancelled']
  const currentIndex = statusOrder.indexOf(order.value.status)

  if (order.value.status === 'cancelled') {
    statuses.push({
      title: 'Cancelled',
      description: 'Order has been cancelled',
      date: '2024-11-14T16:00:00',
      completed: true,
      current: true
    })
  }

  return statuses
})

const cancelOrder = () => {
  order.value.status = 'cancelled'
  showCancelModal.value = false
  // In real app, make API call to cancel order
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
    minimumFractionDigits: 0
  }).format(price)
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-PH', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const formatDateTime = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleString('en-PH', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    processing: 'bg-blue-100 text-blue-800',
    shipped: 'bg-purple-100 text-purple-800',
    delivered: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}
</script>
