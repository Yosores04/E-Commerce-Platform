<template>
  <DefaultLayout>
    <div class="container mx-auto px-4 py-8">
      <!-- Hero Section -->
      <section class="bg-gradient-to-r from-primary-900 via-burgundy-900 to-primary-800 rounded-2xl p-12 text-white mb-12 shadow-xl">
        <div class="max-w-2xl">
          <h1 class="text-5xl font-bold mb-4 font-['Playfair_Display'] bg-gradient-to-r from-gold-300 via-gold-400 to-gold-300 bg-clip-text text-transparent">Welcome to Xerxia</h1>
          <p class="text-xl mb-6 text-neutral-50">Elegance in every purchase. Discover premium quality from curated vendors.</p>
          <router-link to="/products" class="bg-gradient-to-r from-gold-400 to-gold-500 text-primary-900 px-8 py-3 rounded-lg font-semibold hover:from-gold-300 hover:to-gold-400 inline-block transition-all duration-300 shadow-lg">
            Shop Now
          </router-link>
        </div>
      </section>

      <!-- Featured Categories -->
      <section class="mb-12">
        <h2 class="text-3xl font-bold text-primary-900 mb-6 font-['Playfair_Display']">Shop by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div 
            v-for="category in categories"
            :key="category.id"
            class="bg-white border border-neutral-100 rounded-xl shadow-md p-6 text-center cursor-pointer hover:shadow-xl hover:border-gold-300 transition-all duration-300 hover:-translate-y-1"
            @click="goToCategory(category.id)"
          >
            <div class="text-4xl mb-2">{{ category.icon }}</div>
            <h3 class="font-semibold text-primary-900">{{ category.name }}</h3>
          </div>
        </div>
      </section>

      <!-- Featured Products -->
      <section class="mb-12">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-3xl font-bold text-primary-900 font-['Playfair_Display']">Featured Products</h2>
          <router-link to="/products" class="text-primary-900 hover:text-gold-500 font-semibold transition-colors duration-300">
            View All →
          </router-link>
        </div>
        
        <div v-if="isLoading" class="flex justify-center py-12">
          <div class="spinner"></div>
        </div>

        <div v-else-if="products.length > 0" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
          <ProductCard 
            v-for="product in products"
            :key="product.id"
            :product="product"
          />
        </div>

        <div v-else class="text-center py-12 text-gray-500">
          <p>No products available at the moment.</p>
        </div>
      </section>

      <!-- Why Choose Us -->
      <section class="bg-gradient-to-br from-neutral-50 to-white border border-neutral-100 rounded-2xl shadow-lg p-8 mb-12">
        <h2 class="text-3xl font-bold text-primary-900 mb-8 text-center font-['Playfair_Display']">Why Choose Xerxia</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="bg-gradient-to-br from-gold-100 to-gold-200 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
              <svg class="w-8 h-8 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <h3 class="font-semibold text-lg mb-2 text-primary-900">Curated Selection</h3>
            <p class="text-neutral-600">Carefully selected premium products from verified vendors</p>
          </div>
          <div class="text-center">
            <div class="bg-gradient-to-br from-burgundy-100 to-burgundy-200 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
              <svg class="w-8 h-8 text-burgundy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="font-semibold text-lg mb-2 text-primary-900">Secure Payments</h3>
            <p class="text-neutral-600">Multiple secure payment options with buyer protection</p>
          </div>
          <div class="text-center">
            <div class="bg-gradient-to-br from-accent-100 to-accent-200 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
              <svg class="w-8 h-8 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <h3 class="font-semibold text-lg mb-2 text-primary-900">Premium Experience</h3>
            <p class="text-neutral-600">Exceptional service with white-glove delivery</p>
          </div>
        </div>
      </section>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import DefaultLayout from '../layouts/DefaultLayout.vue'
import ProductCard from '../components/ProductCard.vue'
import { productService } from '../services/product'
import { categoryService } from '../services/category'

const router = useRouter()
const products = ref([])
const categories = ref([])
const isLoading = ref(false)
const isCategoriesLoading = ref(false)

onMounted(async () => {
  await Promise.all([loadProducts(), loadCategories()])
})

const loadProducts = async () => {
  isLoading.value = true
  try {
    const response = await productService.getFeaturedProducts(8)
    // Backend returns: { success: true, data: { data: [...], current_page, last_page, etc } }
    if (response.success && response.data) {
      products.value = response.data.data || []
    }
  } catch (error) {
    console.error('Failed to load products:', error)
    products.value = []
  } finally {
    isLoading.value = false
  }
}

const loadCategories = async () => {
  isCategoriesLoading.value = true
  try {
    const response = await categoryService.getCategories({ per_page: 8 })
    // Backend returns: { success: true, data: { data: [...categories] } }
    if (response.success && response.data) {
      // Get parent categories only for homepage display
      const allCategories = response.data.data || []
      categories.value = allCategories
        .filter(cat => !cat.parent_id) // Only parent categories
        .slice(0, 8) // Limit to 8
    }
  } catch (error) {
    console.error('Failed to load categories:', error)
    // Fallback to some categories if API fails
    categories.value = []
  } finally {
    isCategoriesLoading.value = false
  }
}

const goToCategory = (categoryId) => {
  router.push({ name: 'Products', query: { category: categoryId } })
}
</script>
