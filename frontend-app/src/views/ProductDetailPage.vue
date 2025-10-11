<template>
  <DefaultLayout>
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Product Details</h1>
      
      <div v-if="isLoading" class="flex justify-center py-12">
        <div class="spinner"></div>
      </div>

      <div v-else-if="product" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Images -->
        <div>
          <img 
            :src="product.image_url || '/placeholder-product.jpg'" 
            :alt="product.name"
            class="w-full rounded-lg shadow-md"
          />
        </div>

        <!-- Product Info -->
        <div>
          <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>
          
          <div class="flex items-center mb-4">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <span class="ml-2 text-gray-700">{{ product.rating || 'N/A' }}</span>
            </div>
            <span class="mx-2 text-gray-400">|</span>
            <span class="text-gray-700">{{ product.reviews_count || 0 }} Reviews</span>
          </div>

          <div class="mb-6">
            <p class="text-4xl font-bold text-primary-600">Rp {{ formatPrice(product.price) }}</p>
            <p v-if="product.compare_price" class="text-xl text-gray-500 line-through">
              Rp {{ formatPrice(product.compare_price) }}
            </p>
          </div>

          <div class="mb-6">
            <span 
              :class="[
                'badge',
                product.stock > 10 ? 'badge-success' : product.stock > 0 ? 'badge-warning' : 'badge-danger'
              ]"
            >
              {{ product.stock > 0 ? `${product.stock} in stock` : 'Out of stock' }}
            </span>
          </div>

          <div class="mb-6">
            <h3 class="font-semibold text-gray-900 mb-2">Description</h3>
            <p class="text-gray-700">{{ product.description }}</p>
          </div>

          <div class="mb-6">
            <h3 class="font-semibold text-gray-900 mb-2">Vendor</h3>
            <p class="text-gray-700">{{ product.vendor?.shop_name || 'Unknown' }}</p>
          </div>

          <div class="flex space-x-4">
            <button 
              @click="addToCart"
              :disabled="product.stock === 0"
              class="flex-1 btn-primary disabled:opacity-50"
            >
              Add to Cart
            </button>
            <button class="px-6 btn-secondary">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-12">
        <p class="text-xl text-gray-500">Product not found</p>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import DefaultLayout from '../layouts/DefaultLayout.vue'
import { productsService } from '../services/products'
import { useCartStore } from '../stores/cart'

const route = useRoute()
const cartStore = useCartStore()

const product = ref(null)
const isLoading = ref(false)

onMounted(async () => {
  await loadProduct()
})

const loadProduct = async () => {
  isLoading.value = true
  try {
    const response = await productsService.getProduct(route.params.id)
    product.value = response.data
  } catch (error) {
    console.error('Failed to load product:', error)
  } finally {
    isLoading.value = false
  }
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(price)
}

const addToCart = async () => {
  try {
    await cartStore.addItem(product.value.id, 1)
    alert('Product added to cart!')
  } catch (error) {
    console.error('Failed to add to cart:', error)
    alert('Failed to add product to cart')
  }
}
</script>
