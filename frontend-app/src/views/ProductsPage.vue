<template>
  <DefaultLayout>
    <div class="bg-whitesmoke min-h-screen">
      <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">All Products</h1>
            <p class="text-gray-600 mt-1">{{ pagination.total }} products found</p>
          </div>
          
          <!-- Controls -->
          <div class="flex items-center space-x-3">
            <!-- Filter Toggle Button -->
            <button
              @click="showFilters = !showFilters"
              class="lg:hidden flex items-center space-x-2 px-4 py-2 bg-wine text-white rounded-lg hover:bg-wine/90 transition-colors"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
              </svg>
              <span>Filters</span>
              <span v-if="hasActiveFilters" class="bg-white text-wine text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                {{ activeFiltersCount }}
              </span>
            </button>

            <!-- View Toggle -->
            <div class="flex items-center space-x-2 bg-white rounded-lg border border-gray-200 p-1">
              <button
                @click="viewMode = 'grid'"
                :class="[
                  'p-2 rounded transition-colors',
                  viewMode === 'grid' ? 'bg-wine text-white' : 'text-gray-600 hover:bg-gray-100'
                ]"
                title="Grid View"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
              </button>
              <button
                @click="viewMode = 'list'"
                :class="[
                  'p-2 rounded transition-colors',
                  viewMode === 'list' ? 'bg-wine text-white' : 'text-gray-600 hover:bg-gray-100'
                ]"
                title="List View"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
          <!-- Filters Sidebar -->
          <aside 
            :class="[
              'lg:col-span-1 transition-all duration-300',
              showFilters ? 'block' : 'hidden lg:block'
            ]"
          >
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
              <!-- Close button for mobile -->
              <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-900">Filters</h2>
                <div class="flex items-center space-x-2">
                  <button
                    v-if="hasActiveFilters"
                    @click="clearFilters"
                    class="text-sm text-wine hover:underline"
                  >
                    Clear All
                  </button>
                  <button
                    @click="showFilters = false"
                    class="lg:hidden text-gray-400 hover:text-gray-600"
                  >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Search -->
              <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                <div class="relative">
                  <input
                    v-model="filters.search"
                    type="text"
                    placeholder="Search products..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wine focus:border-transparent"
                    @input="debouncedSearch"
                  />
                  <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
              </div>

              <!-- Categories -->
              <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Categories</label>
                <div class="space-y-2 max-h-64 overflow-y-auto">
                  <label
                    v-for="category in categories"
                    :key="category.id"
                    class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 p-2 rounded"
                  >
                    <input
                      type="checkbox"
                      :value="category.id"
                      v-model="filters.selectedCategories"
                      @change="loadProducts"
                      class="w-4 h-4 text-wine border-gray-300 rounded focus:ring-wine"
                    />
                    <span class="text-sm text-gray-700">{{ category.name }}</span>
                  </label>
                </div>
              </div>

              <!-- Price Range Slider -->
              <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                  Price Range
                </label>
                <div class="space-y-4">
                  <div>
                    <input
                      v-model.number="filters.minPrice"
                      type="range"
                      min="0"
                      :max="maxPrice"
                      step="1000"
                      @input="debouncedPriceFilter"
                      class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider-wine"
                    />
                    <div class="flex justify-between text-xs text-gray-600 mt-1">
                      <span>₱{{ formatPrice(filters.minPrice) }}</span>
                      <span>₱{{ formatPrice(filters.maxPrice) }}</span>
                    </div>
                  </div>
                  <div>
                    <input
                      v-model.number="filters.maxPrice"
                      type="range"
                      :min="filters.minPrice"
                      :max="maxPrice"
                      step="1000"
                      @input="debouncedPriceFilter"
                      class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider-wine"
                    />
                  </div>
                  <div class="flex items-center justify-between pt-2 border-t">
                    <div class="text-center">
                      <div class="text-xs text-gray-600 mb-1">Min</div>
                      <input
                        v-model.number="filters.minPrice"
                        type="number"
                        class="w-24 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-wine focus:border-transparent"
                        @change="loadProducts"
                      />
                    </div>
                    <span class="text-gray-400">-</span>
                    <div class="text-center">
                      <div class="text-xs text-gray-600 mb-1">Max</div>
                      <input
                        v-model.number="filters.maxPrice"
                        type="number"
                        class="w-24 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-wine focus:border-transparent"
                        @change="loadProducts"
                      />
                    </div>
                  </div>
                </div>
              </div>

              <!-- Stock Status -->
              <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Availability</label>
                <div class="space-y-2">
                  <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                    <input
                      type="checkbox"
                      v-model="filters.inStockOnly"
                      @change="loadProducts"
                      class="w-4 h-4 text-wine border-gray-300 rounded focus:ring-wine"
                    />
                    <span class="text-sm text-gray-700">In Stock Only</span>
                  </label>
                </div>
              </div>

              <!-- Sort -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sort By</label>
                <select
                  v-model="filters.sort"
                  @change="loadProducts"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wine focus:border-transparent"
                >
                  <option value="newest">Newest First</option>
                  <option value="price_low">Price: Low to High</option>
                  <option value="price_high">Price: High to Low</option>
                  <option value="popular">Most Popular</option>
                  <option value="name">Name: A-Z</option>
                </select>
              </div>
            </div>
          </aside>

          <!-- Products Grid/List -->
          <main 
            :class="[
              'transition-all duration-300',
              showFilters ? 'lg:col-span-3' : 'lg:col-span-4'
            ]"
          >
            <div v-if="isLoading" class="flex justify-center py-12">
              <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-wine"></div>
            </div>

            <div v-else-if="products.length > 0">
              <!-- Grid View -->
              <div
                v-if="viewMode === 'grid'"
                class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8"
              >
                <ProductCard
                  v-for="product in products"
                  :key="product.id"
                  :product="product"
                />
              </div>

              <!-- List View -->
              <div v-else class="space-y-4 mb-8">
                <ProductCardList
                  v-for="product in products"
                  :key="product.id"
                  :product="product"
                />
              </div>

              <!-- Pagination -->
              <div v-if="pagination.last_page > 1" class="flex flex-col items-center space-y-4">
                <nav class="flex items-center space-x-2">
                  <button
                    @click="changePage(1)"
                    :disabled="pagination.current_page === 1"
                    class="px-3 py-2 border border-gray-300 rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-colors"
                    title="First Page"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                  </button>
                  <button
                    @click="changePage(pagination.current_page - 1)"
                    :disabled="pagination.current_page === 1"
                    class="px-4 py-2 border border-gray-300 rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-colors"
                  >
                    Previous
                  </button>
                  
                  <button
                    v-for="page in visiblePages"
                    :key="page"
                    @click="changePage(page)"
                    :class="[
                      'px-4 py-2 border rounded-md transition-colors',
                      page === pagination.current_page
                        ? 'bg-wine text-white border-wine'
                        : 'border-gray-300 hover:bg-gray-50'
                    ]"
                  >
                    {{ page }}
                  </button>
                  
                  <button
                    @click="changePage(pagination.current_page + 1)"
                    :disabled="pagination.current_page === pagination.last_page"
                    class="px-4 py-2 border border-gray-300 rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-colors"
                  >
                    Next
                  </button>
                  <button
                    @click="changePage(pagination.last_page)"
                    :disabled="pagination.current_page === pagination.last_page"
                    class="px-3 py-2 border border-gray-300 rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-colors"
                    title="Last Page"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                    </svg>
                  </button>
                </nav>
                <div class="text-sm text-gray-600">
                  Page {{ pagination.current_page }} of {{ pagination.last_page }} 
                  ({{ pagination.total }} total products)
                </div>
              </div>
            </div>

            <div v-else class="text-center py-12 bg-white rounded-lg shadow-md">
              <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
              </svg>
              <p class="text-xl text-gray-500 font-medium mb-4">No products found</p>
              <p class="text-gray-400 mb-6">Try adjusting your filters or search terms</p>
              <button @click="clearFilters" class="px-6 py-2 bg-wine text-white rounded-lg hover:bg-wine/90 transition-colors">
                Clear All Filters
              </button>
            </div>
          </main>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DefaultLayout from '../layouts/DefaultLayout.vue'
import ProductCard from '../components/ProductCard.vue'
import ProductCardList from '../components/ProductCardList.vue'
import { productService } from '../services/product'
import { categoryService } from '../services/category'

const route = useRoute()
const router = useRouter()

const products = ref([])
const categories = ref([])
const isLoading = ref(false)
const viewMode = ref('grid') // 'grid' or 'list'
const showFilters = ref(true) // Show filters by default on desktop
const maxPrice = ref(100000)

const filters = ref({
  search: route.query.q || '',
  selectedCategories: [],
  sort: route.query.sort || 'newest',
  minPrice: 0,
  maxPrice: 100000,
  inStockOnly: false
})

const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 12,
  total: 0
})

let searchTimeout = null
let priceTimeout = null

const hasActiveFilters = computed(() => {
  return filters.value.search !== '' ||
    filters.value.selectedCategories.length > 0 ||
    filters.value.minPrice > 0 ||
    filters.value.maxPrice < maxPrice.value ||
    filters.value.inStockOnly
})

const activeFiltersCount = computed(() => {
  let count = 0
  if (filters.value.search) count++
  if (filters.value.selectedCategories.length > 0) count++
  if (filters.value.minPrice > 0 || filters.value.maxPrice < maxPrice.value) count++
  if (filters.value.inStockOnly) count++
  return count
})

const visiblePages = computed(() => {
  const pages = []
  const current = pagination.value.current_page
  const last = pagination.value.last_page
  
  // Show max 5 pages
  let start = Math.max(1, current - 2)
  let end = Math.min(last, current + 2)
  
  // Adjust if we're near the beginning or end
  if (end - start < 4) {
    if (start === 1) {
      end = Math.min(last, start + 4)
    } else if (end === last) {
      start = Math.max(1, end - 4)
    }
  }
  
  for (let i = start; i <= end; i++) {
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
  await loadProducts()
})

const loadCategories = async () => {
  try {
    const response = await categoryService.getCategories()
    // Backend returns: { success: true, data: {...} }
    if (response.success && response.data) {
      categories.value = Array.isArray(response.data) ? response.data : response.data.data || []
    } else {
      categories.value = []
    }
  } catch (error) {
    console.error('Failed to load categories:', error)
    categories.value = []
  }
}

const loadProducts = async (page = 1) => {
  isLoading.value = true
  try {
    const params = {
      page,
      per_page: pagination.value.per_page,
      sort_by: filters.value.sort === 'newest' ? 'created_at' : 
               filters.value.sort === 'price_low' ? 'price' : 
               filters.value.sort === 'price_high' ? 'price' : 'created_at',
      sort_order: filters.value.sort === 'price_high' ? 'desc' : 
                  filters.value.sort === 'price_low' ? 'asc' : 'desc'
    }
    
    if (filters.value.search) {
      params.search = filters.value.search
    }
    
    if (filters.value.selectedCategories.length > 0) {
      params.category_id = filters.value.selectedCategories[0] // API takes single category
    }
    
    if (filters.value.minPrice > 0) {
      params.min_price = filters.value.minPrice
    }
    
    if (filters.value.maxPrice < maxPrice.value) {
      params.max_price = filters.value.maxPrice
    }
    
    if (filters.value.inStockOnly) {
      params.in_stock = 1
    }
    
    const response = await productService.getProducts(params)
    
    // Backend returns: { success: true, data: { current_page, data: [], last_page, per_page, total } }
    if (response.success && response.data) {
      products.value = response.data.data || []
      pagination.value = {
        current_page: response.data.current_page || 1,
        last_page: response.data.last_page || 1,
        per_page: response.data.per_page || 15,
        total: response.data.total || 0
      }
    } else {
      products.value = []
    }
  } catch (error) {
    console.error('Failed to load products:', error)
    products.value = []
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

const debouncedPriceFilter = () => {
  if (priceTimeout) clearTimeout(priceTimeout)
  priceTimeout = setTimeout(() => {
    loadProducts()
  }, 300)
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
    selectedCategories: [],
    sort: 'newest',
    minPrice: 0,
    maxPrice: maxPrice.value,
    inStockOnly: false
  }
  router.push({ name: 'Products' })
  loadProducts()
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-PH').format(price)
}
</script>

<style scoped>
/* Custom range slider styling */
.slider-wine::-webkit-slider-thumb {
  appearance: none;
  -webkit-appearance: none;
  width: 18px;
  height: 18px;
  background: #5B2333;
  cursor: pointer;
  border-radius: 50%;
  border: 2px solid white;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.slider-wine::-moz-range-thumb {
  width: 18px;
  height: 18px;
  background: #5B2333;
  cursor: pointer;
  border-radius: 50%;
  border: 2px solid white;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.slider-wine::-webkit-slider-runnable-track {
  background: linear-gradient(to right, #5B2333 0%, #5B2333 var(--value), #e5e7eb var(--value), #e5e7eb 100%);
  height: 8px;
  border-radius: 4px;
}

.slider-wine::-moz-range-track {
  background: #e5e7eb;
  height: 8px;
  border-radius: 4px;
}

.slider-wine::-moz-range-progress {
  background: #5B2333;
  height: 8px;
  border-radius: 4px;
}
</style>
