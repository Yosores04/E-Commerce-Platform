<template>
  <router-link 
    :to="`/products/${product.id}`"
    class="block bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group"
  >
    <div class="flex flex-col sm:flex-row">
      <!-- Product Image -->
      <div class="sm:w-48 h-48 sm:h-auto bg-gray-100 flex-shrink-0 relative overflow-hidden">
        <img 
          :src="primaryImage"
          :alt="product.name"
          @error="handleImageError"
          class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
        />
        
        <!-- Badge -->
        <div v-if="product.compare_price && product.compare_price > product.price" 
          class="absolute top-2 left-2 bg-burgundy text-white px-2 py-1 rounded text-xs font-bold">
          {{ Math.round((1 - product.price / product.compare_price) * 100) }}% OFF
        </div>

        <!-- Stock Badge -->
        <div v-if="product.stock === 0" 
          class="absolute bottom-2 left-2 bg-red-600 text-white px-2 py-1 rounded text-xs font-bold">
          OUT OF STOCK
        </div>
      </div>

      <!-- Product Info -->
      <div class="flex-1 p-6 flex flex-col justify-between">
        <div>
          <!-- Category -->
          <div class="flex items-center space-x-2 mb-2">
            <span class="text-xs text-wine font-medium">{{ product.category?.name || 'Uncategorized' }}</span>
            <span v-if="product.vendor?.shop_name" class="text-gray-400">•</span>
            <span v-if="product.vendor?.shop_name" class="text-xs text-gray-600">{{ product.vendor.shop_name }}</span>
          </div>

          <!-- Product Name -->
          <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-wine transition-colors line-clamp-2">
            {{ product.name }}
          </h3>

          <!-- Description -->
          <p class="text-gray-600 text-sm mb-4 line-clamp-3">
            {{ product.description }}
          </p>

          <!-- Rating -->
          <div class="flex items-center space-x-2 mb-3">
            <div class="flex items-center">
              <svg v-for="i in 5" :key="i" class="w-4 h-4" 
                :class="i <= Math.floor(product.rating || 4) ? 'text-gold' : 'text-gray-300'" 
                fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            </div>
            <span class="text-sm text-gray-600">{{ product.rating || '4.0' }}</span>
            <span class="text-gray-400">•</span>
            <span class="text-sm text-gray-600">{{ product.reviews_count || 0 }} reviews</span>
          </div>
        </div>

        <!-- Price & Action -->
        <div class="flex items-end justify-between mt-4 pt-4 border-t border-gray-100">
          <div>
            <div class="flex items-baseline space-x-2">
              <span class="text-2xl font-bold text-wine">₱{{ formatPrice(product.price) }}</span>
              <span v-if="product.compare_price && product.compare_price > product.price" 
                class="text-sm text-gray-400 line-through">
                ₱{{ formatPrice(product.compare_price) }}
              </span>
            </div>
            
            <!-- Stock Info -->
            <div class="mt-1">
              <span 
                :class="[
                  'inline-flex items-center text-xs font-medium',
                  product.stock > 10 ? 'text-green-600' : product.stock > 0 ? 'text-yellow-600' : 'text-red-600'
                ]"
              >
                <span class="w-2 h-2 rounded-full mr-1" 
                  :class="product.stock > 0 ? 'bg-green-500' : 'bg-red-500'"></span>
                {{ product.stock > 0 ? `${product.stock} in stock` : 'Out of stock' }}
              </span>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="flex space-x-2">
            <button
              @click.prevent="addToWishlist"
              class="p-2 border-2 border-wine text-wine rounded-lg hover:bg-wine hover:text-white transition-all"
              title="Add to Wishlist"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </button>
            <button
              @click.prevent="quickView"
              class="px-4 py-2 bg-wine text-white rounded-lg hover:bg-wine/90 transition-all font-medium"
            >
              View Details
            </button>
          </div>
        </div>
      </div>
    </div>
  </router-link>
</template>

<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
})

const imageError = ref(false)

const primaryImage = computed(() => {
  if (imageError.value) {
    return 'https://via.placeholder.com/800x800/5B2333/F7F4F3?text=No+Image'
  }
  
  if (props.product.images && props.product.images.length > 0) {
    const primary = props.product.images.find(img => img.is_primary)
    return primary ? primary.image_path : props.product.images[0].image_path
  }
  
  return 'https://via.placeholder.com/800x800/5B2333/F7F4F3?text=No+Image'
})

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-PH').format(price)
}

const handleImageError = () => {
  imageError.value = true
}

const addToWishlist = () => {
  // TODO: Implement wishlist functionality
  console.log('Add to wishlist:', props.product.id)
}

const quickView = () => {
  // Navigate to detail page
  window.location.href = `/products/${props.product.id}`
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  overflow: hidden;
}
</style>
