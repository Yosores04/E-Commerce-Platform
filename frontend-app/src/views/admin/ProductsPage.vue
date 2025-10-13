<template>
  <div class="min-h-screen bg-white-smoke">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-xerxia-wine">Products Management</h1>
            <p class="text-gray-600 mt-1">Manage your product inventory</p>
          </div>
          <button
            @click="showAddModal = true"
            class="px-6 py-3 bg-xerxia-wine text-white rounded-lg hover:bg-burgundy transition-colors font-medium flex items-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Product
          </button>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Filters and Search -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="md:col-span-2">
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Search products..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
            />
          </div>
          <select
            v-model="filterCategory"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
          >
            <option value="">All Categories</option>
            <option value="Red Wine">Red Wine</option>
            <option value="White Wine">White Wine</option>
            <option value="Sparkling">Sparkling</option>
            <option value="Rosé">Rosé</option>
          </select>
          <select
            v-model="filterStatus"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
          >
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="out-of-stock">Out of Stock</option>
          </select>
        </div>
      </div>

      <!-- Products Table -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Product
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Category
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Price
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Stock
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
              v-for="product in filteredProducts"
              :key="product.id"
              class="hover:bg-gray-50"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <img
                    :src="product.image"
                    :alt="product.name"
                    class="w-12 h-12 rounded-lg object-cover"
                  />
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                    <div class="text-sm text-gray-500">SKU: {{ product.sku }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ product.category }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-xerxia-wine">₱{{ formatNumber(product.price) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ product.stock }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="getStatusClass(product.status)"
                  class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                >
                  {{ product.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="editProduct(product)"
                  class="text-xerxia-wine hover:text-burgundy mr-3"
                >
                  Edit
                </button>
                <button
                  @click="deleteProduct(product.id)"
                  class="text-red-600 hover:text-red-900"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-6 flex items-center justify-between">
        <p class="text-sm text-gray-600">
          Showing {{ filteredProducts.length }} of {{ products.length }} products
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
            Next
          </button>
        </div>
      </div>
    </div>

    <!-- Add/Edit Product Modal -->
    <div
      v-if="showAddModal || showEditModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closeModal"
    >
      <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6">
          <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">
              {{ showEditModal ? 'Edit Product' : 'Add New Product' }}
            </h3>
            <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <form @submit.prevent="saveProduct" class="p-6 space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Product Name *
              </label>
              <input
                type="text"
                v-model="formData.name"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                SKU *
              </label>
              <input
                type="text"
                v-model="formData.sku"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Category *
              </label>
              <select
                v-model="formData.category"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
              >
                <option value="">Select Category</option>
                <option value="Red Wine">Red Wine</option>
                <option value="White Wine">White Wine</option>
                <option value="Sparkling">Sparkling</option>
                <option value="Rosé">Rosé</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Price (₱) *
              </label>
              <input
                type="number"
                v-model="formData.price"
                required
                min="0"
                step="0.01"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Stock *
              </label>
              <input
                type="number"
                v-model="formData.stock"
                required
                min="0"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Status *
              </label>
              <select
                v-model="formData.status"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="out-of-stock">Out of Stock</option>
              </select>
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Description
              </label>
              <textarea
                v-model="formData.description"
                rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
              ></textarea>
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Image URL
              </label>
              <input
                type="url"
                v-model="formData.image"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
              />
            </div>
          </div>

          <div class="flex gap-3 pt-4">
            <button
              type="button"
              @click="closeModal"
              class="flex-1 px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="flex-1 px-6 py-2 bg-xerxia-wine text-white rounded-lg hover:bg-burgundy transition-colors font-medium"
            >
              {{ showEditModal ? 'Update Product' : 'Add Product' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// Search and filters
const searchQuery = ref('')
const filterCategory = ref('')
const filterStatus = ref('')

// Modals
const showAddModal = ref(false)
const showEditModal = ref(false)

// Form data
const formData = ref({
  name: '',
  sku: '',
  category: '',
  price: 0,
  stock: 0,
  status: 'active',
  description: '',
  image: ''
})

// Mock products data
const products = ref([
  {
    id: 1,
    name: 'Merlot Reserve 2020',
    sku: 'WN-MR-2020',
    category: 'Red Wine',
    price: 1599,
    stock: 45,
    status: 'active',
    image: 'https://picsum.photos/seed/wine1/100/100'
  },
  {
    id: 2,
    name: 'Cabernet Sauvignon',
    sku: 'WN-CS-2021',
    category: 'Red Wine',
    price: 1299,
    stock: 32,
    status: 'active',
    image: 'https://picsum.photos/seed/wine2/100/100'
  },
  {
    id: 3,
    name: 'Chardonnay Classic',
    sku: 'WN-CC-2022',
    category: 'White Wine',
    price: 999,
    stock: 0,
    status: 'out-of-stock',
    image: 'https://picsum.photos/seed/wine3/100/100'
  },
  {
    id: 4,
    name: 'Champagne Deluxe',
    sku: 'WN-CD-2020',
    category: 'Sparkling',
    price: 2599,
    stock: 18,
    status: 'active',
    image: 'https://picsum.photos/seed/wine4/100/100'
  },
  {
    id: 5,
    name: 'Rosé Wine Special',
    sku: 'WN-RS-2022',
    category: 'Rosé',
    price: 1199,
    stock: 28,
    status: 'active',
    image: 'https://picsum.photos/seed/wine5/100/100'
  },
  {
    id: 6,
    name: 'Pinot Noir Vintage',
    sku: 'WN-PN-2019',
    category: 'Red Wine',
    price: 1799,
    stock: 12,
    status: 'inactive',
    image: 'https://picsum.photos/seed/wine6/100/100'
  }
])

// Filtered products
const filteredProducts = computed(() => {
  return products.value.filter(product => {
    const matchesSearch = product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                          product.sku.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesCategory = !filterCategory.value || product.category === filterCategory.value
    const matchesStatus = !filterStatus.value || product.status === filterStatus.value
    
    return matchesSearch && matchesCategory && matchesStatus
  })
})

const editProduct = (product) => {
  formData.value = { ...product }
  showEditModal.value = true
}

const deleteProduct = (id) => {
  if (confirm('Are you sure you want to delete this product?')) {
    products.value = products.value.filter(p => p.id !== id)
  }
}

const saveProduct = () => {
  if (showEditModal.value) {
    // Update existing product
    const index = products.value.findIndex(p => p.id === formData.value.id)
    if (index !== -1) {
      products.value[index] = { ...formData.value }
    }
  } else {
    // Add new product
    const newProduct = {
      ...formData.value,
      id: Date.now(),
      image: formData.value.image || 'https://picsum.photos/seed/wine' + Date.now() + '/100/100'
    }
    products.value.push(newProduct)
  }
  closeModal()
}

const closeModal = () => {
  showAddModal.value = false
  showEditModal.value = false
  formData.value = {
    name: '',
    sku: '',
    category: '',
    price: 0,
    stock: 0,
    status: 'active',
    description: '',
    image: ''
  }
}

const formatNumber = (num) => {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
}

const getStatusClass = (status) => {
  const classes = {
    'active': 'bg-green-100 text-green-800',
    'inactive': 'bg-gray-100 text-gray-800',
    'out-of-stock': 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}
</script>
