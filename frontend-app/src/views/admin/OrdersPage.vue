<template>
  <div class="min-h-screen bg-white-smoke">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div>
          <h1 class="text-3xl font-bold text-xerxia-wine">Orders Management</h1>
          <p class="text-gray-600 mt-1">Manage and fulfill customer orders</p>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
          <p class="text-sm text-gray-600 mb-1">Pending</p>
          <p class="text-2xl font-bold text-yellow-600">{{ orderStats.pending }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
          <p class="text-sm text-gray-600 mb-1">Processing</p>
          <p class="text-2xl font-bold text-blue-600">{{ orderStats.processing }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
          <p class="text-sm text-gray-600 mb-1">Shipped</p>
          <p class="text-2xl font-bold text-purple-600">{{ orderStats.shipped }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
          <p class="text-sm text-gray-600 mb-1">Delivered</p>
          <p class="text-2xl font-bold text-green-600">{{ orderStats.delivered }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
          <p class="text-sm text-gray-600 mb-1">Cancelled</p>
          <p class="text-2xl font-bold text-red-600">{{ orderStats.cancelled }}</p>
        </div>
      </div>

      <!-- Filters and Search -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="md:col-span-2">
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Search orders..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
            />
          </div>
          <select
            v-model="filterStatus"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
          >
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
          </select>
          <input
            type="date"
            v-model="filterDate"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
          />
        </div>
      </div>

      <!-- Orders Table -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Order
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Customer
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Date
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Total
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="order in filteredOrders"
              :key="order.id"
              class="hover:bg-gray-50"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ order.orderNumber }}</div>
                <div class="text-sm text-gray-500">{{ order.itemsCount }} items</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ order.customer }}</div>
                <div class="text-sm text-gray-500">{{ order.email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatDate(order.date) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-xerxia-wine">₱{{ formatNumber(order.total) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <select
                  v-model="order.status"
                  @change="updateOrderStatus(order)"
                  :class="getStatusClass(order.status)"
                  class="px-2 py-1 text-xs font-semibold rounded-full border-0 cursor-pointer"
                >
                  <option value="pending">Pending</option>
                  <option value="processing">Processing</option>
                  <option value="shipped">Shipped</option>
                  <option value="delivered">Delivered</option>
                  <option value="cancelled">Cancelled</option>
                </select>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="viewOrder(order)"
                  class="text-xerxia-wine hover:text-burgundy mr-3"
                >
                  View
                </button>
                <button
                  @click="printInvoice(order)"
                  class="text-gray-600 hover:text-gray-900"
                >
                  Print
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-6 flex items-center justify-between">
        <p class="text-sm text-gray-600">
          Showing {{ filteredOrders.length }} of {{ orders.length }} orders
        </p>
        <div class="flex gap-2">
          <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
            Previous
          </button>
          <button class="px-4 py-2 bg-xerxia-wine text-white rounded-lg">
            1
          </button>
          <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
            2
          </button>
          <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
            3
          </button>
          <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
            Next
          </button>
        </div>
      </div>
    </div>

    <!-- Order Details Modal -->
    <div
      v-if="showOrderModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="showOrderModal = false"
    >
      <div class="bg-white rounded-lg max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xl font-bold text-gray-900">Order {{ selectedOrder.orderNumber }}</h3>
              <p class="text-sm text-gray-600">{{ formatDate(selectedOrder.date) }}</p>
            </div>
            <button @click="showOrderModal = false" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <div class="p-6">
          <!-- Customer Info -->
          <div class="mb-6">
            <h4 class="font-semibold text-gray-900 mb-3">Customer Information</h4>
            <div class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm">
              <p><span class="font-medium">Name:</span> {{ selectedOrder.customer }}</p>
              <p><span class="font-medium">Email:</span> {{ selectedOrder.email }}</p>
              <p><span class="font-medium">Phone:</span> {{ selectedOrder.phone }}</p>
              <p><span class="font-medium">Address:</span> {{ selectedOrder.address }}</p>
            </div>
          </div>

          <!-- Order Items -->
          <div class="mb-6">
            <h4 class="font-semibold text-gray-900 mb-3">Order Items</h4>
            <div class="space-y-3">
              <div
                v-for="item in selectedOrder.items"
                :key="item.id"
                class="flex items-center gap-4 p-3 border border-gray-200 rounded-lg"
              >
                <img
                  :src="item.image"
                  :alt="item.name"
                  class="w-16 h-16 rounded-lg object-cover"
                />
                <div class="flex-1">
                  <p class="font-medium text-gray-900">{{ item.name }}</p>
                  <p class="text-sm text-gray-600">Quantity: {{ item.quantity }}</p>
                </div>
                <p class="font-semibold text-xerxia-wine">₱{{ formatNumber(item.price * item.quantity) }}</p>
              </div>
            </div>
          </div>

          <!-- Order Summary -->
          <div class="border-t border-gray-200 pt-4">
            <div class="space-y-2 text-sm">
              <div class="flex justify-between text-gray-600">
                <span>Subtotal</span>
                <span>₱{{ formatNumber(selectedOrder.subtotal) }}</span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Shipping</span>
                <span>₱{{ formatNumber(selectedOrder.shipping) }}</span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Tax</span>
                <span>₱{{ formatNumber(selectedOrder.tax) }}</span>
              </div>
              <div class="flex justify-between font-bold text-lg pt-2 border-t border-gray-200">
                <span>Total</span>
                <span class="text-xerxia-wine">₱{{ formatNumber(selectedOrder.total) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// Search and filters
const searchQuery = ref('')
const filterStatus = ref('')
const filterDate = ref('')

// Modal
const showOrderModal = ref(false)
const selectedOrder = ref({})

// Order stats
const orderStats = ref({
  pending: 45,
  processing: 82,
  shipped: 134,
  delivered: 2891,
  cancelled: 95
})

// Mock orders data
const orders = ref([
  {
    id: 1,
    orderNumber: 'ORD-2024-156',
    customer: 'Juan Dela Cruz',
    email: 'juan@example.com',
    phone: '+63 912 345 6789',
    address: '123 Makati Avenue, Makati, Metro Manila',
    date: '2024-10-12',
    itemsCount: 3,
    total: 5971,
    subtotal: 5197,
    shipping: 150,
    tax: 624,
    status: 'processing',
    items: [
      { id: 1, name: 'Merlot Reserve 2020', quantity: 2, price: 1599, image: 'https://picsum.photos/seed/wine1/100/100' },
      { id: 2, name: 'Cabernet Sauvignon', quantity: 1, price: 1299, image: 'https://picsum.photos/seed/wine2/100/100' },
      { id: 3, name: 'Chardonnay Classic', quantity: 1, price: 999, image: 'https://picsum.photos/seed/wine3/100/100' }
    ]
  },
  {
    id: 2,
    orderNumber: 'ORD-2024-155',
    customer: 'Maria Santos',
    email: 'maria@example.com',
    phone: '+63 923 456 7890',
    address: '456 Quezon Avenue, Quezon City, Metro Manila',
    date: '2024-10-11',
    itemsCount: 2,
    total: 3899,
    subtotal: 3498,
    shipping: 150,
    tax: 251,
    status: 'shipped',
    items: [
      { id: 1, name: 'Champagne Deluxe', quantity: 1, price: 2599, image: 'https://picsum.photos/seed/wine4/100/100' },
      { id: 2, name: 'Sparkling Wine', quantity: 1, price: 899, image: 'https://picsum.photos/seed/wine5/100/100' }
    ]
  },
  {
    id: 3,
    orderNumber: 'ORD-2024-154',
    customer: 'Pedro Reyes',
    email: 'pedro@example.com',
    phone: '+63 934 567 8901',
    address: '789 Roxas Blvd, Pasay, Metro Manila',
    date: '2024-10-10',
    itemsCount: 4,
    total: 8499,
    subtotal: 7396,
    shipping: 350,
    tax: 753,
    status: 'delivered',
    items: [
      { id: 1, name: 'Pinot Noir Vintage', quantity: 2, price: 1799, image: 'https://picsum.photos/seed/wine6/100/100' },
      { id: 2, name: 'Rosé Wine Special', quantity: 2, price: 1199, image: 'https://picsum.photos/seed/wine7/100/100' }
    ]
  },
  {
    id: 4,
    orderNumber: 'ORD-2024-153',
    customer: 'Ana Garcia',
    email: 'ana@example.com',
    phone: '+63 945 678 9012',
    address: '321 Ortigas Avenue, Pasig, Metro Manila',
    date: '2024-10-13',
    itemsCount: 1,
    total: 1299,
    subtotal: 1099,
    shipping: 150,
    tax: 50,
    status: 'pending',
    items: [
      { id: 1, name: 'Port Wine Special', quantity: 1, price: 1099, image: 'https://picsum.photos/seed/wine8/100/100' }
    ]
  },
  {
    id: 5,
    orderNumber: 'ORD-2024-152',
    customer: 'Carlos Lopez',
    email: 'carlos@example.com',
    phone: '+63 956 789 0123',
    address: '654 Shaw Blvd, Mandaluyong, Metro Manila',
    date: '2024-10-09',
    itemsCount: 2,
    total: 4299,
    subtotal: 3698,
    shipping: 350,
    tax: 251,
    status: 'processing',
    items: [
      { id: 1, name: 'Merlot Reserve 2020', quantity: 1, price: 1599, image: 'https://picsum.photos/seed/wine1/100/100' },
      { id: 2, name: 'Cabernet Sauvignon', quantity: 1, price: 1299, image: 'https://picsum.photos/seed/wine2/100/100' }
    ]
  }
])

// Filtered orders
const filteredOrders = computed(() => {
  return orders.value.filter(order => {
    const matchesSearch = order.orderNumber.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                          order.customer.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesStatus = !filterStatus.value || order.status === filterStatus.value
    const matchesDate = !filterDate.value || order.date === filterDate.value
    
    return matchesSearch && matchesStatus && matchesDate
  })
})

const viewOrder = (order) => {
  selectedOrder.value = order
  showOrderModal.value = true
}

const updateOrderStatus = (order) => {
  console.log('Order status updated:', order)
  // In real app, make API call to update order status
}

const printInvoice = (order) => {
  console.log('Print invoice for:', order.orderNumber)
  // In real app, generate and print invoice
}

const formatNumber = (num) => {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-PH', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const getStatusClass = (status) => {
  const classes = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'processing': 'bg-blue-100 text-blue-800',
    'shipped': 'bg-purple-100 text-purple-800',
    'delivered': 'bg-green-100 text-green-800',
    'cancelled': 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}
</script>
