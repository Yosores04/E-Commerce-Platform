<template>
  <DefaultLayout>
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-6">All Products</h1>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="form-label">Search</label>
            <input
              v-model="filters.search"
              type="text"
              placeholder="Search products..."
              class="form-input"
              @input="debouncedSearch"
            />
          </div>
          <div>
            <label class="form-label">Category</label>
            <select v-model="filters.category" @change="loadProducts" class="form-input">
              <option value="">All Categories</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>
          <div>
            <label class="form-label">Sort By</label>
            <select v-model="filters.sort" @change="loadProducts" class="form-input">
              <option value="newest">Newest</option>
              <option value="price_low">Price: Low to High</option>
              <option value="price_high">Price: High to Low</option>
              <option value="popular">Most Popular</option>
            </select>
          </div>
          <div>
            <label class="form-label">Price Range</label>
            <select v-model="filters.priceRange" @change="loadProducts" class="form-input">
              <option value="">All Prices</option>
              <option value="0-100000">Under Rp 100,000</option>
              <option value="100000-500000">Rp 100,000 - 500,000</option>
              <option value="500000-1000000">Rp 500,000 - 1,000,000</option>
              <option value="1000000-">Over Rp 1,000,000</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Products Grid -->
      <div v-if="isLoading" class="flex justify-center py-12">
        <div class="spinner"></div>
      </div>

      <div v-else-if="products.length > 0">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
          <ProductCard 
            v-for="product in products"
            :key="product.id"
            :product="product"
          />
        </div>

        <!-- Pagination -->
        <div v-if="pagination.total > pagination.per_page" class="flex justify-center">
          <nav class="flex space-x-2">
            <button
              @click="changePage(pagination.current_page - 1)"
              :disabled="pagination.current_page === 1"
              class="px-4 py-2 border border-gray-300 rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
            >
              Previous
            </button>
            <button
              v-for="page in visiblePages"
              :key="page"
              @click="changePage(page)"
              :class="[
                'px-4 py-2 border rounded-md',
                page === pagination.current_page
                  ? 'bg-primary-600 text-white border-primary-600'
                  : 'border-gray-300 hover:bg-gray-50'
              ]"
            >
              {{ page }}
            </button>
            <button
              @click="changePage(pagination.current_page + 1)"
              :disabled="pagination.current_page === pagination.last_page"
              class="px-4 py-2 border border-gray-300 rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
            >
              Next
            </button>
          </nav>
        </div>
      </div>

      <div v-else class="text-center py-12 text-gray-500">
        <p class="text-xl">No products found.</p>
        <button @click="clearFilters" class="mt-4 btn-primary">
          Clear Filters
        </button>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DefaultLayout from '../layouts/DefaultLayout.vue'
import ProductCard from '../components/ProductCard.vue'
import { productsService } from '../services/products'

const route = useRoute()
const router = useRouter()

const products = ref([])
const categories = ref([])
const isLoading = ref(false)
const filters = ref({
  search: route.query.q || '',
  category: route.query.category || '',
  sort: route.query.sort || 'newest',
  priceRange: route.query.price || ''
})
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 12,
  total: 0
})

let searchTimeout = null

const visiblePages = computed(() => {
  const pages = []
  const current = pagination.value.current_page
  const last = pagination.value.last_page
  
  for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
    pages.push(i)
  }
  
  return pages
})

onMounted(async () => {
  await loadCategories()
  await loadProducts()
})

watch(() => route.query, async (newQuery) => {
  filters.value.search = newQuery.q || ''
  filters.value.category = newQuery.category || ''
  await loadProducts()
})

const loadCategories = async () => {
  try {
    const response = await productsService.getCategories()
    categories.value = response.data
  } catch (error) {
    console.error('Failed to load categories:', error)
  }
}

const loadProducts = async (page = 1) => {
  isLoading.value = true
  try {
    const params = {
      page,
      limit: pagination.value.per_page,
      sort: filters.value.sort
    }
    
    if (filters.value.search) {
      params.q = filters.value.search
    }
    if (filters.value.category) {
      params.category_id = filters.value.category
    }
    if (filters.value.priceRange) {
      const [min, max] = filters.value.priceRange.split('-')
      if (min) params.min_price = min
      if (max) params.max_price = max
    }
    
    const response = await productsService.getProducts(params)
    products.value = response.data
    
    if (response.meta) {
      pagination.value = {
        current_page: response.meta.current_page,
        last_page: response.meta.last_page,
        per_page: response.meta.per_page,
        total: response.meta.total
      }
    }
  } catch (error) {
    console.error('Failed to load products:', error)
  } finally {
    isLoading.value = false
  }
}

const debouncedSearch = () => {
  if (searchTimeout) clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    loadProducts()
  }, 500)
}

const changePage = (page) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    loadProducts(page)
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const clearFilters = () => {
  filters.value = {
    search: '',
    category: '',
    sort: 'newest',
    priceRange: ''
  }
  router.push({ name: 'Products' })
  loadProducts()
}
</script>
