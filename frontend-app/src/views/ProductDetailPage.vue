<template>
  <DefaultLayout>
    <div class="bg-whitesmoke min-h-screen">
      <!-- Breadcrumb -->
      <div class="bg-white border-b border-gray-200">
        <div class="container mx-auto px-4 py-3">
          <nav class="flex items-center space-x-2 text-sm">
            <router-link to="/" class="text-wine hover:text-wine/80 transition-colors">Home</router-link>
            <span class="text-gray-400">/</span>
            <router-link to="/products" class="text-wine hover:text-wine/80 transition-colors">Products</router-link>
            <span class="text-gray-400">/</span>
            <span class="text-gray-600">{{ product?.name || 'Loading...' }}</span>
          </nav>
        </div>
      </div>

      <div class="container mx-auto px-4 py-8">
        <div v-if="isLoading" class="flex justify-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-wine"></div>
        </div>

        <div v-else-if="product">
          <!-- Main Product Section -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Image Gallery -->
            <div class="space-y-4">
              <!-- Main Image -->
              <div class="bg-white rounded-lg shadow-md overflow-hidden aspect-square">
                <img 
                  :src="selectedImage"
                  :alt="product.name"
                  @error="handleImageError"
                  class="w-full h-full object-cover"
                />
              </div>

              <!-- Thumbnail Gallery -->
              <div v-if="productImages.length > 1" class="grid grid-cols-5 gap-2">
                <button
                  v-for="(image, index) in productImages"
                  :key="index"
                  @click="selectedImage = image.image_path"
                  :class="[
                    'aspect-square rounded-lg overflow-hidden border-2 transition-all hover:border-wine',
                    selectedImage === image.image_path ? 'border-wine ring-2 ring-wine/20' : 'border-gray-200'
                  ]"
                >
                  <img 
                    :src="image.image_path" 
                    :alt="`${product.name} - Image ${index + 1}`"
                    @error="handleImageError"
                    class="w-full h-full object-cover"
                  />
                </button>
              </div>
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
              <!-- Title & Category -->
              <div>
                <div class="flex items-center space-x-2 mb-2">
                  <span class="text-sm text-wine font-medium">{{ product.category?.name || 'Uncategorized' }}</span>
                  <span v-if="product.vendor?.shop_name" class="text-gray-400">•</span>
                  <span v-if="product.vendor?.shop_name" class="text-sm text-gray-600">by {{ product.vendor.shop_name }}</span>
                </div>
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">{{ product.name }}</h1>
              </div>

              <!-- Rating -->
              <div class="flex items-center space-x-3">
                <div class="flex items-center">
                  <svg v-for="i in 5" :key="i" class="w-5 h-5" :class="i <= Math.floor(product.rating || 4) ? 'text-gold' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                </div>
                <span class="text-gray-700">{{ product.rating || '4.0' }}</span>
                <span class="text-gray-400">•</span>
                <span class="text-gray-700">{{ product.reviews_count || 0 }} Reviews</span>
              </div>

              <!-- Price -->
              <div>
                <div class="flex items-baseline space-x-3">
                  <p class="text-4xl font-bold text-wine">₱{{ formatPrice(product.price) }}</p>
                  <p v-if="product.compare_price && product.compare_price > product.price" class="text-xl text-gray-400 line-through">
                    ₱{{ formatPrice(product.compare_price) }}
                  </p>
                  <span v-if="product.compare_price && product.compare_price > product.price" class="px-2 py-1 bg-burgundy text-white text-sm font-medium rounded">
                    {{ Math.round((1 - product.price / product.compare_price) * 100) }}% OFF
                  </span>
                </div>
              </div>

              <!-- Stock Status -->
              <div>
                <span 
                  :class="[
                    'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                    product.stock > 10 ? 'bg-green-100 text-green-800' : product.stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'
                  ]"
                >
                  <span class="w-2 h-2 rounded-full mr-2" :class="product.stock > 0 ? 'bg-green-500' : 'bg-red-500'"></span>
                  {{ product.stock > 0 ? `${product.stock} in stock` : 'Out of stock' }}
                </span>
              </div>

              <!-- Short Description -->
              <div class="border-t border-b border-gray-200 py-4">
                <p class="text-gray-700 leading-relaxed">{{ product.description }}</p>
              </div>

              <!-- Quantity & Add to Cart -->
              <div class="space-y-4">
                <div class="flex items-center space-x-4">
                  <label class="text-gray-700 font-medium">Quantity:</label>
                  <div class="flex items-center border border-gray-300 rounded-lg">
                    <button 
                      @click="quantity = Math.max(1, quantity - 1)"
                      class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition-colors"
                    >
                      -
                    </button>
                    <input 
                      v-model.number="quantity"
                      type="number"
                      min="1"
                      :max="product.stock"
                      class="w-16 text-center border-x border-gray-300 py-2 focus:outline-none"
                    />
                    <button 
                      @click="quantity = Math.min(product.stock, quantity + 1)"
                      class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition-colors"
                    >
                      +
                    </button>
                  </div>
                </div>

                <div class="flex space-x-3">
                  <button 
                    @click="addToCart"
                    :disabled="product.stock === 0"
                    class="flex-1 bg-wine text-white py-3 px-6 rounded-lg font-semibold hover:bg-wine/90 disabled:bg-gray-300 disabled:cursor-not-allowed transition-all shadow-md hover:shadow-lg"
                  >
                    <span class="flex items-center justify-center space-x-2">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                      <span>Add to Cart</span>
                    </span>
                  </button>
                  <button class="px-6 py-3 border-2 border-wine text-wine rounded-lg hover:bg-wine hover:text-white transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Tabs Section -->
          <div class="bg-white rounded-lg shadow-md mb-12">
            <div class="border-b border-gray-200">
              <div class="flex space-x-8 px-6">
                <button
                  v-for="tab in tabs"
                  :key="tab.id"
                  @click="activeTab = tab.id"
                  :class="[
                    'py-4 px-2 font-semibold border-b-2 transition-colors',
                    activeTab === tab.id
                      ? 'border-wine text-wine'
                      : 'border-transparent text-gray-600 hover:text-wine'
                  ]"
                >
                  {{ tab.label }}
                </button>
              </div>
            </div>

            <div class="p-6">
              <!-- Description Tab -->
              <div v-show="activeTab === 'description'" class="prose max-w-none">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Product Description</h3>
                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ product.description }}</p>
              </div>

              <!-- Specifications Tab -->
              <div v-show="activeTab === 'specifications'" class="space-y-3">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Specifications</h3>
                <div class="grid grid-cols-2 gap-4">
                  <div class="flex flex-col space-y-1">
                    <span class="text-sm text-gray-600">SKU</span>
                    <span class="font-medium text-gray-900">{{ product.sku || 'N/A' }}</span>
                  </div>
                  <div class="flex flex-col space-y-1">
                    <span class="text-sm text-gray-600">Stock</span>
                    <span class="font-medium text-gray-900">{{ product.stock }}</span>
                  </div>
                  <div class="flex flex-col space-y-1">
                    <span class="text-sm text-gray-600">Category</span>
                    <span class="font-medium text-gray-900">{{ product.category?.name || 'N/A' }}</span>
                  </div>
                  <div class="flex flex-col space-y-1">
                    <span class="text-sm text-gray-600">Vendor</span>
                    <span class="font-medium text-gray-900">{{ product.vendor?.shop_name || 'N/A' }}</span>
                  </div>
                </div>
              </div>

              <!-- Reviews Tab -->
              <div v-show="activeTab === 'reviews'">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Customer Reviews</h3>
                
                <!-- Reviews Summary -->
                <div class="flex items-start space-x-8 mb-8 pb-8 border-b">
                  <div class="text-center">
                    <div class="text-5xl font-bold text-wine mb-2">{{ product.rating || '4.0' }}</div>
                    <div class="flex items-center justify-center mb-1">
                      <svg v-for="i in 5" :key="i" class="w-4 h-4" :class="i <= Math.floor(product.rating || 4) ? 'text-gold' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118ननl1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    </div>
                    <div class="text-sm text-gray-600">{{ product.reviews_count || 0 }} reviews</div>
                  </div>
                  <div class="flex-1 space-y-2">
                    <div v-for="rating in [5, 4, 3, 2, 1]" :key="rating" class="flex items-center space-x-3">
                      <span class="text-sm text-gray-600 w-12">{{ rating }} star</span>
                      <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div class="bg-gold h-2 rounded-full" :style="{ width: '0%' }"></div>
                      </div>
                      <span class="text-sm text-gray-600 w-8">0</span>
                    </div>
                  </div>
                </div>

                <!-- No Reviews Yet -->
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                  <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                  </svg>
                  <p class="text-gray-600 text-lg font-medium mb-2">No reviews yet</p>
                  <p class="text-gray-500">Be the first to review this product!</p>
                  <button class="mt-4 px-6 py-2 bg-wine text-white rounded-lg hover:bg-wine/90 transition-colors">
                    Write a Review
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Related Products -->
          <div v-if="relatedProducts.length > 0">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
              <ProductCard
                v-for="relatedProduct in relatedProducts"
                :key="relatedProduct.id"
                :product="relatedProduct"
              />
            </div>
          </div>
        </div>

        <div v-else class="text-center py-12">
          <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-xl text-gray-500 font-medium">Product not found</p>
          <router-link to="/products" class="mt-4 inline-block text-wine hover:underline">
            Browse all products
          </router-link>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import DefaultLayout from '../layouts/DefaultLayout.vue'
import ProductCard from '../components/ProductCard.vue'
import { productService } from '../services/product'
import { useCartStore } from '../stores/cart'

const route = useRoute()
const cartStore = useCartStore()

const product = ref(null)
const isLoading = ref(false)
const quantity = ref(1)
const selectedImage = ref('')
const activeTab = ref('description')
const relatedProducts = ref([])

const tabs = [
  { id: 'description', label: 'Description' },
  { id: 'specifications', label: 'Specifications' },
  { id: 'reviews', label: 'Reviews' }
]

const productImages = computed(() => {
  if (!product.value || !product.value.images || product.value.images.length === 0) {
    return [{
      image_path: 'https://via.placeholder.com/800x800/5B2333/F7F4F3?text=No+Image',
      is_primary: true
    }]
  }
  return product.value.images.sort((a, b) => {
    if (a.is_primary) return -1
    if (b.is_primary) return 1
    return (a.order || 0) - (b.order || 0)
  })
})

onMounted(async () => {
  await loadProduct()
  await loadRelatedProducts()
})

const loadProduct = async () => {
  isLoading.value = true
  try {
    const response = await productService.getProduct(route.params.id)
    // Backend returns: { success: true, data: { product object } }
    product.value = response.success ? response.data : null
    
    // Set initial selected image to primary or first image
    if (productImages.value.length > 0) {
      selectedImage.value = productImages.value[0].image_path
    }
  } catch (error) {
    console.error('Failed to load product:', error)
  } finally {
    isLoading.value = false
  }
}

const loadRelatedProducts = async () => {
  try {
    if (!product.value?.category_id) return
    
    const response = await productService.getRelatedProducts(route.params.id, 4)
    // Backend returns: { success: true, data: [...products] }
    if (response.success) {
      relatedProducts.value = Array.isArray(response.data) ? response.data : response.data.data || []
    }
  } catch (error) {
    console.error('Failed to load related products:', error)
  }
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-PH').format(price)
}

const addToCart = async () => {
  try {
    await cartStore.addItem(product.value.id, quantity.value)
    
    // Simple success notification
    const notification = document.createElement('div')
    notification.className = 'fixed top-20 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in'
    notification.innerHTML = `
      <div class="flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span>Added to cart!</span>
      </div>
    `
    document.body.appendChild(notification)
    
    setTimeout(() => {
      notification.remove()
    }, 3000)
  } catch (error) {
    console.error('Failed to add to cart:', error)
    alert('Failed to add product to cart')
  }
}

const handleImageError = (event) => {
  event.target.src = 'https://via.placeholder.com/800x800/5B2333/F7F4F3?text=Image+Not+Available'
}
</script>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.3s ease-out;
}

/* Hide number input spinner */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  appearance: textfield;
  -moz-appearance: textfield;
}
</style>
