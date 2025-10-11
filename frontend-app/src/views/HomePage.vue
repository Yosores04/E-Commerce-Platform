<template>
  <DefaultLayout>
    <div class="container mx-auto px-4 py-8">
      <!-- Hero Section -->
      <section class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-lg p-12 text-white mb-12">
        <div class="max-w-2xl">
          <h1 class="text-4xl font-bold mb-4">Welcome to IndoMarket</h1>
          <p class="text-xl mb-6">Discover quality products from trusted vendors across Indonesia</p>
          <router-link to="/products" class="bg-white text-primary-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 inline-block">
            Shop Now
          </router-link>
        </div>
      </section>

      <!-- Featured Categories -->
      <section class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Shop by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div 
            v-for="category in categories"
            :key="category.id"
            class="bg-white rounded-lg shadow-md p-6 text-center cursor-pointer hover:shadow-xl transition-shadow"
            @click="goToCategory(category.id)"
          >
            <div class="text-4xl mb-2">{{ category.icon }}</div>
            <h3 class="font-semibold text-gray-900">{{ category.name }}</h3>
          </div>
        </div>
      </section>

      <!-- Featured Products -->
      <section class="mb-12">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Featured Products</h2>
          <router-link to="/products" class="text-primary-600 hover:text-primary-700 font-semibold">
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
      <section class="bg-white rounded-lg shadow-md p-8 mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Why Choose IndoMarket</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <h3 class="font-semibold text-lg mb-2">Verified Vendors</h3>
            <p class="text-gray-600">All vendors are carefully verified to ensure quality</p>
          </div>
          <div class="text-center">
            <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="font-semibold text-lg mb-2">Secure Payments</h3>
            <p class="text-gray-600">Multiple secure payment options available</p>
          </div>
          <div class="text-center">
            <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <h3 class="font-semibold text-lg mb-2">Fast Delivery</h3>
            <p class="text-gray-600">Quick and reliable shipping nationwide</p>
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
import { productsService } from '../services/products'

const router = useRouter()
const products = ref([])
const categories = ref([
  { id: 1, name: 'Electronics', icon: '📱' },
  { id: 2, name: 'Fashion', icon: '👗' },
  { id: 3, name: 'Home & Living', icon: '🏠' },
  { id: 4, name: 'Beauty', icon: '💄' },
  { id: 5, name: 'Sports', icon: '⚽' },
  { id: 6, name: 'Books', icon: '📚' },
  { id: 7, name: 'Food', icon: '🍔' },
  { id: 8, name: 'Toys', icon: '🧸' }
])
const isLoading = ref(false)

onMounted(async () => {
  await loadProducts()
})

const loadProducts = async () => {
  isLoading.value = true
  try {
    const response = await productsService.getProducts({ limit: 8 })
    products.value = response.data
  } catch (error) {
    console.error('Failed to load products:', error)
  } finally {
    isLoading.value = false
  }
}

const goToCategory = (categoryId) => {
  router.push({ name: 'Products', query: { category: categoryId } })
}
</script>
