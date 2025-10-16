<template>
  <AdminLayout>
    <div class="p-8">
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-3xl font-bold text-xerxia-wine">Orders Management</h1>
        <p class="text-gray-600 mt-1">Manage and fulfill customer orders</p>
      </div>
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

      <!-- Loading State -->
      <div v-if="loading" class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-6">
        <p class="font-medium">Loading orders...</p>
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
                <div class="text-sm font-medium text-gray-900">{{ order.order_number || order.orderNumber }}</div>
                <div class="text-sm text-gray-500">{{ order.itemsCount }} items</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ getCustomerName(order) }}</div>
                <div class="text-sm text-gray-500">{{ order.email || order.user?.email || 'N/A' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatDate(order.created_at || order.date) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-xerxia-wine">₱{{ formatNumber(order.total) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <select
                  v-model="order.status"
                  @change="updateOrderStatus(order, order.status)"
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
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '../../layouts/AdminLayout.vue'
import { adminService } from '../../services/admin'

// Search and filters
const searchQuery = ref('')
const filterStatus = ref('')
const filterDate = ref('')

// Modal
const showOrderModal = ref(false)
const selectedOrder = ref({})

// Loading state
const loading = ref(false)

// Order stats
const orderStats = ref({
  pending: 0,
  processing: 0,
  shipped: 0,
  delivered: 0,
  cancelled: 0
})

// Orders data from API
const orders = ref([])

// Fetch orders from API
const fetchOrders = async () => {
  try {
    loading.value = true
    console.log('Fetching orders from API...')
    const response = await adminService.getOrders({
      search: searchQuery.value || undefined,
      status: filterStatus.value || undefined,
      date: filterDate.value || undefined
    })
    
    console.log('Orders API response:', response)
    orders.value = (response.data?.data || response.data || response).map(order => ({
      ...order,
      itemsCount: order.items?.length || order.order_items?.length || 0,
      items: order.items || order.order_items || []
    }))
    console.log('Orders loaded:', orders.value.length)
    
    // Calculate stats
    orderStats.value = {
      pending: orders.value.filter(o => o.status === 'pending').length,
      processing: orders.value.filter(o => o.status === 'processing').length,
      shipped: orders.value.filter(o => o.status === 'shipped').length,
      delivered: orders.value.filter(o => o.status === 'delivered').length,
      cancelled: orders.value.filter(o => o.status === 'cancelled').length
    }
  } catch (error) {
    console.error('Error fetching orders:', error)
  } finally {
    loading.value = false
  }
}

// Filtered orders
const filteredOrders = computed(() => {
  return orders.value.filter(order => {
    const matchesSearch = order.order_number?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                          order.customer?.name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                          order.user?.name?.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesStatus = !filterStatus.value || order.status === filterStatus.value
    const matchesDate = !filterDate.value || order.created_at?.startsWith(filterDate.value)
    
    return matchesSearch && matchesStatus && matchesDate
  })
})

const viewOrder = (order) => {
  selectedOrder.value = order
  showOrderModal.value = true
}

const updateOrderStatus = async (order, newStatus) => {
  try {
    await adminService.updateOrderStatus(order.id, newStatus)
    await fetchOrders()
  } catch (error) {
    console.error('Error updating order status:', error)
    alert('Failed to update order status')
  }
}

const printInvoice = (order) => {
  console.log('Print invoice for:', order.order_number || order.orderNumber)
  window.print()
}

const formatNumber = (num) => {
  return num?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') || '0'
}

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
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

const getCustomerName = (order) => {
  return order.customer?.name || order.user?.name || order.customer || 'N/A'
}

// Load data on mount
onMounted(() => {
  fetchOrders()
})
</script>
