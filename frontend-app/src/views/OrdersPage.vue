<template>
  <div class="min-h-screen bg-white-smoke py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-xerxia-wine mb-2">My Orders</h1>
        <p class="text-gray-600">Track and manage your orders</p>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Status Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Order Status
            </label>
            <select
              v-model="filters.status"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
            >
              <option value="">All Orders</option>
              <option value="pending">Pending</option>
              <option value="processing">Processing</option>
              <option value="shipped">Shipped</option>
              <option value="delivered">Delivered</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>

          <!-- Date From -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              From Date
            </label>
            <input
              type="date"
              v-model="filters.dateFrom"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
            />
          </div>

          <!-- Date To -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              To Date
            </label>
            <input
              type="date"
              v-model="filters.dateTo"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
            />
          </div>
        </div>

        <!-- Filter Actions -->
        <div class="flex justify-end mt-4 space-x-3">
          <button
            @click="clearFilters"
            class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 font-medium"
          >
            Clear Filters
          </button>
          <button
            @click="applyFilters"
            class="px-6 py-2 bg-xerxia-wine text-white rounded-lg hover:bg-burgundy transition-colors font-medium"
          >
            Apply Filters
          </button>
        </div>
      </div>

      <!-- Orders List -->
      <div v-if="filteredOrders.length > 0" class="space-y-4">
        <div
          v-for="order in paginatedOrders"
          :key="order.id"
          class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow"
        >
          <!-- Order Header -->
          <div class="p-6 border-b border-gray-200">
            <div class="flex flex-wrap items-center justify-between gap-4">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 mb-2">
                  <h3 class="text-lg font-semibold text-gray-900">
                    Order {{ order.orderNumber }}
                  </h3>
                  <span
                    :class="getStatusClass(order.status)"
                    class="px-3 py-1 rounded-full text-xs font-semibold"
                  >
                    {{ order.status.charAt(0).toUpperCase() + order.status.slice(1) }}
                  </span>
                </div>
                <p class="text-sm text-gray-600">
                  Placed on {{ formatDate(order.date) }} • {{ order.itemsCount }} item{{ order.itemsCount !== 1 ? 's' : '' }}
                </p>
              </div>
              <div class="text-right">
                <p class="text-sm text-gray-600 mb-1">Total Amount</p>
                <p class="text-2xl font-bold text-xerxia-wine">{{ formatPrice(order.total) }}</p>
              </div>
            </div>
          </div>

          <!-- Order Items Preview -->
          <div class="p-6 bg-gray-50">
            <div class="flex items-center space-x-4 overflow-x-auto">
              <img
                v-for="(item, index) in order.items.slice(0, 4)"
                :key="index"
                :src="item.image"
                :alt="item.name"
                class="w-16 h-16 rounded-lg object-cover border border-gray-200"
              />
              <div
                v-if="order.items.length > 4"
                class="w-16 h-16 rounded-lg bg-gray-200 flex items-center justify-center text-sm font-medium text-gray-600"
              >
                +{{ order.items.length - 4 }}
              </div>
            </div>
          </div>

          <!-- Order Actions -->
          <div class="p-6 bg-white border-t border-gray-200">
            <div class="flex flex-wrap gap-3">
              <router-link
                :to="`/orders/${order.id}`"
                class="flex-1 sm:flex-none px-6 py-2 bg-xerxia-wine text-white rounded-lg hover:bg-burgundy transition-colors font-medium text-center"
              >
                View Details
              </router-link>
              <button
                v-if="order.status === 'shipped' || order.status === 'processing'"
                class="flex-1 sm:flex-none px-6 py-2 border-2 border-xerxia-wine text-xerxia-wine rounded-lg hover:bg-xerxia-wine hover:text-white transition-colors font-medium"
              >
                Track Order
              </button>
              <button
                v-if="order.status === 'pending' || order.status === 'processing'"
                @click="showCancelConfirm(order.id)"
                class="flex-1 sm:flex-none px-6 py-2 border-2 border-red-500 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-colors font-medium"
              >
                Cancel Order
              </button>
              <button
                v-if="order.status === 'delivered'"
                class="flex-1 sm:flex-none px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
              >
                Request Return
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div
        v-else
        class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center"
      >
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-6">
          <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No orders found</h3>
        <p class="text-gray-600 mb-6">
          {{ filters.status || filters.dateFrom || filters.dateTo 
            ? 'Try adjusting your filters to see more orders.' 
            : 'You haven\'t placed any orders yet.' }}
        </p>
        <router-link
          to="/products"
          class="inline-block px-8 py-3 bg-xerxia-wine text-white rounded-lg hover:bg-burgundy transition-colors font-medium"
        >
          Start Shopping
        </router-link>
      </div>

      <!-- Pagination -->
      <div
        v-if="filteredOrders.length > ordersPerPage"
        class="mt-8 flex justify-center"
      >
        <nav class="flex items-center space-x-2">
          <button
            @click="currentPage--"
            :disabled="currentPage === 1"
            :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
          >
            Previous
          </button>
          <button
            v-for="page in totalPages"
            :key="page"
            @click="currentPage = page"
            :class="{
              'bg-xerxia-wine text-white': currentPage === page,
              'border-gray-300 hover:bg-gray-50': currentPage !== page
            }"
            class="px-4 py-2 border rounded-lg transition-colors"
          >
            {{ page }}
          </button>
          <button
            @click="currentPage++"
            :disabled="currentPage === totalPages"
            :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
          >
            Next
          </button>
        </nav>
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
            Are you sure you want to cancel this order? This action cannot be undone.
          </p>
          <div class="flex gap-3">
            <button
              @click="showCancelModal = false"
              class="flex-1 px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
            >
              Keep Order
            </button>
            <button
              @click="confirmCancel"
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

// Filters
const filters = ref({
  status: '',
  dateFrom: '',
  dateTo: ''
})

// Pagination
const currentPage = ref(1)
const ordersPerPage = 5

// Mock orders data
const orders = ref([
  {
    id: 1,
    orderNumber: 'ORD-2024-001',
    date: '2024-11-10',
    status: 'delivered',
    itemsCount: 3,
    total: 5971,
    items: [
      { 
        name: 'Red Wine Premium', 
        image: 'https://picsum.photos/seed/wine1/200/200' 
      },
      { 
        name: 'White Wine Collection', 
        image: 'https://picsum.photos/seed/wine2/200/200' 
      },
      { 
        name: 'Rosé Wine Special', 
        image: 'https://picsum.photos/seed/wine3/200/200' 
      }
    ]
  },
  {
    id: 2,
    orderNumber: 'ORD-2024-002',
    date: '2024-11-12',
    status: 'shipped',
    itemsCount: 2,
    total: 3899,
    items: [
      { 
        name: 'Champagne Deluxe', 
        image: 'https://picsum.photos/seed/wine4/200/200' 
      },
      { 
        name: 'Sparkling Wine', 
        image: 'https://picsum.photos/seed/wine5/200/200' 
      }
    ]
  },
  {
    id: 3,
    orderNumber: 'ORD-2024-003',
    date: '2024-11-14',
    status: 'processing',
    itemsCount: 5,
    total: 8499,
    items: [
      { 
        name: 'Merlot Reserve', 
        image: 'https://picsum.photos/seed/wine6/200/200' 
      },
      { 
        name: 'Cabernet Sauvignon', 
        image: 'https://picsum.photos/seed/wine7/200/200' 
      },
      { 
        name: 'Pinot Noir Vintage', 
        image: 'https://picsum.photos/seed/wine8/200/200' 
      },
      { 
        name: 'Chardonnay Classic', 
        image: 'https://picsum.photos/seed/wine9/200/200' 
      },
      { 
        name: 'Sauvignon Blanc', 
        image: 'https://picsum.photos/seed/wine10/200/200' 
      }
    ]
  },
  {
    id: 4,
    orderNumber: 'ORD-2024-004',
    date: '2024-11-15',
    status: 'pending',
    itemsCount: 1,
    total: 1299,
    items: [
      { 
        name: 'Port Wine Special', 
        image: 'https://picsum.photos/seed/wine11/200/200' 
      }
    ]
  },
  {
    id: 5,
    orderNumber: 'ORD-2024-005',
    date: '2024-10-28',
    status: 'cancelled',
    itemsCount: 2,
    total: 2599,
    items: [
      { 
        name: 'Dessert Wine', 
        image: 'https://picsum.photos/seed/wine12/200/200' 
      },
      { 
        name: 'Ice Wine Premium', 
        image: 'https://picsum.photos/seed/wine13/200/200' 
      }
    ]
  }
])

// Cancel modal
const showCancelModal = ref(false)
const orderToCancel = ref(null)

const showCancelConfirm = (orderId) => {
  orderToCancel.value = orderId
  showCancelModal.value = true
}

const confirmCancel = () => {
  const order = orders.value.find(o => o.id === orderToCancel.value)
  if (order) {
    order.status = 'cancelled'
  }
  showCancelModal.value = false
  orderToCancel.value = null
}

// Filtered orders
const filteredOrders = computed(() => {
  return orders.value.filter(order => {
    // Status filter
    if (filters.value.status && order.status !== filters.value.status) {
      return false
    }
    
    // Date from filter
    if (filters.value.dateFrom && order.date < filters.value.dateFrom) {
      return false
    }
    
    // Date to filter
    if (filters.value.dateTo && order.date > filters.value.dateTo) {
      return false
    }
    
    return true
  })
})

// Paginated orders
const paginatedOrders = computed(() => {
  const start = (currentPage.value - 1) * ordersPerPage
  const end = start + ordersPerPage
  return filteredOrders.value.slice(start, end)
})

// Total pages
const totalPages = computed(() => {
  return Math.ceil(filteredOrders.value.length / ordersPerPage)
})

const clearFilters = () => {
  filters.value = {
    status: '',
    dateFrom: '',
    dateTo: ''
  }
  currentPage.value = 1
}

const applyFilters = () => {
  currentPage.value = 1
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

<style scoped>
/* Custom scrollbar for items preview */
.overflow-x-auto::-webkit-scrollbar {
  height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: #5B2333;
  border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: #7A1E3A;
}
</style>
