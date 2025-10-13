<template>
  <DefaultLayout>
    <div class="bg-whitesmoke min-h-screen">
      <div class="container mx-auto px-4 py-12">
        <!-- Success Animation & Message -->
        <div class="max-w-3xl mx-auto">
          <div class="bg-white rounded-lg shadow-lg p-8 text-center mb-8">
            <!-- Success Icon with Animation -->
            <div class="mb-6 flex justify-center">
              <div class="relative">
                <div class="absolute inset-0 bg-green-100 rounded-full animate-ping opacity-75"></div>
                <div class="relative bg-green-500 text-white rounded-full p-6">
                  <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              </div>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-3">Order Placed Successfully!</h1>
            <p class="text-gray-600 mb-6">Thank you for your purchase. Your order has been confirmed.</p>

            <!-- Order Number -->
            <div class="bg-wine/5 border-2 border-wine rounded-lg p-6 mb-6">
              <div class="text-sm text-gray-600 mb-1">Order Number</div>
              <div class="text-2xl font-bold text-wine">{{ orderNumber }}</div>
              <div class="text-sm text-gray-600 mt-2">
                Order Date: {{ formatDate(orderDate) }}
              </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
              <router-link
                to="/orders"
                class="px-6 py-3 bg-wine text-white rounded-lg font-semibold hover:bg-wine/90 transition-all shadow-md hover:shadow-lg"
              >
                View Order Details
              </router-link>
              <router-link
                to="/products"
                class="px-6 py-3 border-2 border-wine text-wine rounded-lg font-semibold hover:bg-wine hover:text-white transition-all"
              >
                Continue Shopping
              </router-link>
            </div>
          </div>

          <!-- Order Summary -->
          <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Order Summary</h2>

            <!-- Items Ordered -->
            <div class="space-y-4 mb-6">
              <div v-for="item in orderItems" :key="item.id" class="flex space-x-4 py-4 border-b border-gray-100 last:border-b-0">
                <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                  <img 
                    :src="item.image"
                    :alt="item.name"
                    class="w-full h-full object-cover"
                  />
                </div>
                <div class="flex-1 min-w-0">
                  <h3 class="font-semibold text-gray-900">{{ item.name }}</h3>
                  <p class="text-sm text-gray-600">Qty: {{ item.quantity }}</p>
                  <p class="text-wine font-bold mt-1">₱{{ formatPrice(item.price) }}</p>
                </div>
                <div class="text-right">
                  <div class="font-bold text-gray-900">₱{{ formatPrice(item.price * item.quantity) }}</div>
                </div>
              </div>
            </div>

            <!-- Order Totals -->
            <div class="space-y-2 pt-4 border-t border-gray-200">
              <div class="flex justify-between text-gray-700">
                <span>Subtotal</span>
                <span class="font-medium">₱{{ formatPrice(subtotal) }}</span>
              </div>
              <div class="flex justify-between text-gray-700">
                <span>Shipping</span>
                <span class="font-medium">₱{{ formatPrice(shipping) }}</span>
              </div>
              <div class="flex justify-between text-gray-700">
                <span>Tax</span>
                <span class="font-medium">₱{{ formatPrice(tax) }}</span>
              </div>
              <div class="pt-3 border-t border-gray-200 flex justify-between items-center">
                <span class="text-lg font-bold text-gray-900">Total</span>
                <span class="text-2xl font-bold text-wine">₱{{ formatPrice(total) }}</span>
              </div>
            </div>
          </div>

          <!-- Delivery & Payment Info -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Shipping Address -->
            <div class="bg-white rounded-lg shadow-md p-6">
              <h3 class="font-bold text-gray-900 mb-4 flex items-center space-x-2">
                <svg class="w-5 h-5 text-wine" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Shipping Address</span>
              </h3>
              <div class="text-sm text-gray-700 space-y-1">
                <div class="font-medium text-gray-900">{{ shippingAddress.name }}</div>
                <div>{{ shippingAddress.address }}</div>
                <div>{{ shippingAddress.city }}, {{ shippingAddress.state }} {{ shippingAddress.postal_code }}</div>
                <div>{{ shippingAddress.phone }}</div>
              </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white rounded-lg shadow-md p-6">
              <h3 class="font-bold text-gray-900 mb-4 flex items-center space-x-2">
                <svg class="w-5 h-5 text-wine" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span>Payment Method</span>
              </h3>
              <div class="text-sm text-gray-700">
                <div class="font-medium text-gray-900 mb-2">{{ paymentMethod }}</div>
                <div class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  Payment Confirmed
                </div>
              </div>
            </div>
          </div>

          <!-- Estimated Delivery -->
          <div class="bg-gradient-to-r from-wine to-burgundy text-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="font-bold text-lg mb-2">Estimated Delivery</h3>
                <p class="text-white/90">{{ estimatedDelivery }}</p>
              </div>
              <div>
                <svg class="w-16 h-16 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                </svg>
              </div>
            </div>
          </div>

          <!-- What's Next -->
          <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-bold text-gray-900 mb-4">What Happens Next?</h3>
            <div class="space-y-4">
              <div class="flex space-x-4">
                <div class="flex-shrink-0 w-8 h-8 bg-wine text-white rounded-full flex items-center justify-center font-bold text-sm">
                  1
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900">Order Confirmation</h4>
                  <p class="text-sm text-gray-600">You'll receive an email confirmation shortly with your order details.</p>
                </div>
              </div>
              <div class="flex space-x-4">
                <div class="flex-shrink-0 w-8 h-8 bg-wine text-white rounded-full flex items-center justify-center font-bold text-sm">
                  2
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900">Order Processing</h4>
                  <p class="text-sm text-gray-600">Our vendors will prepare your items for shipment within 1-2 business days.</p>
                </div>
              </div>
              <div class="flex space-x-4">
                <div class="flex-shrink-0 w-8 h-8 bg-wine text-white rounded-full flex items-center justify-center font-bold text-sm">
                  3
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900">Shipping Updates</h4>
                  <p class="text-sm text-gray-600">Track your package in real-time through your order dashboard.</p>
                </div>
              </div>
              <div class="flex space-x-4">
                <div class="flex-shrink-0 w-8 h-8 bg-wine text-white rounded-full flex items-center justify-center font-bold text-sm">
                  4
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900">Delivery & Enjoy</h4>
                  <p class="text-sm text-gray-600">Your order will be delivered to your doorstep. Enjoy your purchase!</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Contact Support -->
          <div class="mt-8 text-center text-sm text-gray-600">
            <p>Need help with your order? <a href="#" class="text-wine hover:underline font-medium">Contact Support</a></p>
          </div>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DefaultLayout from '../layouts/DefaultLayout.vue'

const route = useRoute()
const router = useRouter()

// Mock order data (in real app, this would come from API or route params)
const orderNumber = ref('ORD-' + Math.random().toString(36).substr(2, 9).toUpperCase())
const orderDate = ref(new Date())

const orderItems = ref([
  {
    id: 1,
    name: 'Sample Product 1',
    quantity: 2,
    price: 1299,
    image: 'https://picsum.photos/200/200?random=1'
  },
  {
    id: 2,
    name: 'Sample Product 2',
    quantity: 1,
    price: 2599,
    image: 'https://picsum.photos/200/200?random=2'
  }
])

const subtotal = ref(5197)
const shipping = ref(150)
const tax = ref(624) // 12%
const total = ref(5971)

const shippingAddress = ref({
  name: 'Juan Dela Cruz',
  address: 'Unit 123, Building A, Makati Avenue',
  city: 'Makati',
  state: 'Metro Manila',
  postal_code: '1210',
  phone: '+63 912 345 6789'
})

const paymentMethod = ref('Credit Card ending in 4242')
const estimatedDelivery = ref('November 15-17, 2025')

onMounted(() => {
  // Scroll to top
  window.scrollTo({ top: 0, behavior: 'smooth' })
})

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-PH').format(price)
}

const formatDate = (date) => {
  return new Intl.DateTimeFormat('en-PH', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date)
}
</script>

<style scoped>
@keyframes ping {
  75%, 100% {
    transform: scale(2);
    opacity: 0;
  }
}

.animate-ping {
  animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
}
</style>
