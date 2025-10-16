<template>
  <AdminLayout>
    <div class="p-8">
      <!-- Debug Info -->
      <div class="bg-gray-100 border border-gray-300 rounded-lg p-4 mb-4 text-sm">
        <p><strong>Debug Info:</strong></p>
        <p>Products Count: {{ products.length }}</p>
        <p>Loading: {{ loading }}</p>
        <p>Error: {{ error || 'None' }}</p>
        <p>Categories Count: {{ categories.length }}</p>
        <p>API Base URL: http://127.0.0.1:8000/api</p>
        <p>Auth Token: {{ authStore.token ? 'Present ✓' : 'Missing ✗' }}</p>
        <p>User: {{ authStore.user?.name || 'Not logged in' }} ({{ authStore.user?.email }})</p>
        <button @click="testApiConnection" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded mr-2">
          Test API Connection
        </button>
        <button @click="testDatabase" class="mt-2 px-4 py-2 bg-green-500 text-white rounded">
          Check Database
        </button>
      </div>
      
      <!-- Page Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 mb-2">Products Management</h1>
          <p class="text-gray-600">Manage your product inventory</p>
        </div>
        <button
          @click="showAddModal = true"
          class="px-6 py-3 bg-gradient-to-r from-wine to-burgundy-600 text-white rounded-lg hover:shadow-lg transition-all font-semibold flex items-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Add Product
        </button>
      </div>
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

      <!-- Loading State -->
      <div v-if="loading" class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-6">
        <p class="font-medium">Loading products...</p>
      </div>

      <!-- Error State -->
      <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <p class="font-medium">{{ error }}</p>
        <button @click="fetchProducts" class="mt-2 text-sm underline">Try Again</button>
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
                <div class="text-sm text-gray-900">{{ getCategoryName(product) }}</div>
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
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '../../layouts/AdminLayout.vue'
import { productService } from '../../services/product'
import { categoryService } from '../../services/category'
import { useAuthStore } from '../../stores/auth'
import api from '../../services/api'

// Auth store
const authStore = useAuthStore()

// Search and filters
const searchQuery = ref('')
const filterCategory = ref('')
const filterStatus = ref('')

// Modals
const showAddModal = ref(false)
const showEditModal = ref(false)

// Loading state
const loading = ref(false)
const error = ref(null)

// Form data
const formData = ref({
  name: '',
  sku: '',
  category_id: '',
  price: 0,
  stock: 0,
  status: 'active',
  description: '',
  image: ''
})

// Data from API
const products = ref([])
const categories = ref([])

// Fetch products from API
const fetchProducts = async () => {
  try {
    loading.value = true
    error.value = null
    console.log('Fetching products from API...')
    const response = await productService.getProducts()
    console.log('Products API response:', response)
    // Extract products from paginated response
    products.value = response.data?.data || response.data || response
    console.log('Products loaded:', products.value.length)
  } catch (err) {
    console.error('Error fetching products:', err)
    console.error('Error details:', err.response?.data || err.message)
    error.value = `Failed to load products: ${err.response?.data?.message || err.message}`
  } finally {
    loading.value = false
  }
}

// Fetch categories from API
const fetchCategories = async () => {
  try {
    const response = await categoryService.getCategories()
    categories.value = response.data?.data || response.data || response
  } catch (err) {
    console.error('Error fetching categories:', err)
  }
}

// Filtered products
const filteredProducts = computed(() => {
  return products.value.filter(product => {
    const matchesSearch = product.name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                          product.sku?.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesCategory = !filterCategory.value || product.category_id == filterCategory.value
    const matchesStatus = !filterStatus.value || product.status === filterStatus.value
    
    return matchesSearch && matchesCategory && matchesStatus
  })
})

const editProduct = (product) => {
  formData.value = { 
    ...product,
    category_id: product.category_id || product.category?.id || ''
  }
  showEditModal.value = true
}

const deleteProduct = async (id) => {
  if (confirm('Are you sure you want to delete this product?')) {
    try {
      await productService.deleteProduct(id)
      await fetchProducts()
    } catch (err) {
      console.error('Error deleting product:', err)
      alert('Failed to delete product')
    }
  }
}

const saveProduct = async () => {
  try {
    loading.value = true
    if (showEditModal.value) {
      // Update existing product
      await productService.updateProduct(formData.value.id, formData.value)
    } else {
      // Add new product
      await productService.createProduct(formData.value)
    }
    await fetchProducts()
    closeModal()
  } catch (err) {
    console.error('Error saving product:', err)
    alert('Failed to save product')
  } finally {
    loading.value = false
  }
}

const closeModal = () => {
  showAddModal.value = false
  showEditModal.value = false
  formData.value = {
    name: '',
    sku: '',
    category_id: '',
    price: 0,
    stock: 0,
    status: 'active',
    description: '',
    image: ''
  }
}

// Test API connection
const testApiConnection = async () => {
  try {
    console.log('Testing API connection...')
    console.log('Token:', authStore.token)
    const response = await api.get('/products')
    console.log('Products response:', response)
    alert(`API Test Success! Found ${response.data?.data?.length || 0} products`)
  } catch (err) {
    console.error('API Test Error:', err)
    alert(`API Error: ${err.response?.status} - ${err.response?.data?.message || err.message}`)
  }
}

// Test database directly
const testDatabase = async () => {
  try {
    const response = await api.get('/debug/products')
    console.log('Debug response:', response.data)
    alert(`Database Check:\nTotal Products: ${response.data.total_products}\nActive Products: ${response.data.active_products}\nCategories: ${response.data.categories_count}`)
  } catch (err) {
    console.error('Debug Error:', err)
    alert(`Debug Error: ${err.message}`)
  }
}

const formatNumber = (num) => {
  return num?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') || '0'
}

const getStatusClass = (status) => {
  const classes = {
    'active': 'bg-green-100 text-green-800',
    'inactive': 'bg-gray-100 text-gray-800',
    'out-of-stock': 'bg-red-100 text-red-800',
    'out_of_stock': 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getCategoryName = (product) => {
  if (product.category?.name) return product.category.name
  const cat = categories.value.find(c => c.id === product.category_id)
  return cat?.name || 'N/A'
}

// Load data on mount
onMounted(() => {
  fetchProducts()
  fetchCategories()
})
</script>
