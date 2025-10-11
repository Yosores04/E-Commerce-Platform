<template>
  <div 
    class="group relative bg-white rounded-2xl overflow-hidden shadow-soft hover:shadow-soft-lg transition-all duration-300 cursor-pointer transform hover:-translate-y-1"
    @click="goToProduct"
  >
    <!-- Product Image with overlay -->
    <div class="relative overflow-hidden aspect-square">
      <img 
        :src="product.image_url || '/placeholder-product.jpg'" 
        :alt="product.name"
        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
      />
      
      <!-- Gradient overlay on hover -->
      <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
      
      <!-- Badges -->
      <div class="absolute top-3 right-3 flex flex-col gap-2">
        <span 
          v-if="product.stock <= 5 && product.stock > 0"
          class="px-3 py-1 bg-amber-500 text-white text-xs font-semibold rounded-full shadow-lg backdrop-blur-sm"
        >
          Only {{ product.stock }} left
        </span>
        <span 
          v-if="product.stock === 0"
          class="px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded-full shadow-lg backdrop-blur-sm"
        >
          Sold Out
        </span>
        <span 
          v-if="product.compare_price"
          class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full shadow-lg backdrop-blur-sm"
        >
          -{{ Math.round((1 - product.price / product.compare_price) * 100) }}%
        </span>
      </div>

      <!-- Quick action buttons (visible on hover) -->
      <div class="absolute bottom-3 left-3 right-3 flex gap-2 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
        <button
          @click.stop="addToCart"
          :disabled="product.stock === 0"
          class="flex-1 bg-white/95 backdrop-blur-sm text-primary-700 py-2.5 rounded-xl font-semibold hover:bg-primary-600 hover:text-white transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-lg flex items-center justify-center space-x-2"
        >
          <ShoppingCartIcon class="w-5 h-5" />
          <span v-if="product.stock === 0">Sold Out</span>
          <span v-else>Add to Cart</span>
        </button>
        <button
          @click.stop="toggleWishlist"
          class="p-2.5 bg-white/95 backdrop-blur-sm text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-lg"
        >
          <HeartIcon :class="isWishlisted ? 'fill-current' : ''" class="w-5 h-5" />
        </button>
      </div>
    </div>

    <!-- Product Info -->
    <div class="p-4">
      <!-- Vendor -->
      <div class="flex items-center gap-2 mb-2">
        <BuildingStorefrontIcon class="w-4 h-4 text-neutral-400" />
        <p class="text-xs text-neutral-500">{{ product.vendor?.shop_name || 'Unknown' }}</p>
      </div>

      <!-- Product Name -->
      <h3 class="text-base font-semibold text-neutral-900 mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors">
        {{ product.name }}
      </h3>
      
      <!-- Rating -->
      <div v-if="product.rating" class="flex items-center gap-2 mb-3">
        <div class="flex items-center">
          <StarIcon 
            v-for="i in 5" 
            :key="i"
            :class="i <= Math.round(product.rating) ? 'text-amber-400' : 'text-neutral-300'"
            class="w-4 h-4 fill-current"
          />
        </div>
        <span class="text-sm text-neutral-600">({{ product.rating }})</span>
        <span class="text-xs text-neutral-400">• {{ product.reviews_count || 0 }} reviews</span>
      </div>

      <!-- Price -->
      <div class="flex items-end justify-between">
        <div>
          <p class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-transparent">
            Rp {{ formatPrice(product.price) }}
          </p>
          <p v-if="product.compare_price" class="text-sm text-neutral-400 line-through">
            Rp {{ formatPrice(product.compare_price) }}
          </p>
        </div>
        
        <!-- Stock indicator -->
        <div class="flex items-center gap-1">
          <div :class="[
            'w-2 h-2 rounded-full',
            product.stock > 10 ? 'bg-green-500' : product.stock > 0 ? 'bg-amber-500' : 'bg-red-500'
          ]"></div>
          <span class="text-xs text-neutral-500">
            {{ product.stock > 0 ? 'In Stock' : 'Out of Stock' }}
          </span>
        </div>
      </div>
    </div>

    <!-- Hover glow effect -->
    <div class="absolute inset-0 rounded-2xl ring-2 ring-transparent group-hover:ring-primary-500/20 transition-all pointer-events-none"></div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { 
  ShoppingCartIcon, 
  HeartIcon, 
  StarIcon,
  BuildingStorefrontIcon 
} from '@heroicons/vue/24/outline'
import { useCartStore } from '../stores/cart'

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
})

const router = useRouter()
const cartStore = useCartStore()
const isWishlisted = ref(false)

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(price)
}

const goToProduct = () => {
  router.push({ name: 'ProductDetail', params: { id: props.product.id } })
}

const addToCart = async () => {
  try {
    await cartStore.addItem(props.product.id, 1)
    // Show success message (you can use a toast library here)
    showToast('Product added to cart! 🎉')
  } catch (error) {
    console.error('Failed to add to cart:', error)
    showToast('Failed to add product to cart', 'error')
  }
}

const toggleWishlist = () => {
  isWishlisted.value = !isWishlisted.value
  // TODO: Implement wishlist API call
  showToast(isWishlisted.value ? 'Added to wishlist ❤️' : 'Removed from wishlist')
}

const showToast = (message, type = 'success') => {
  // Temporary alert - TODO: Replace with proper toast notification
  console.log(`[${type.toUpperCase()}]`, message)
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.aspect-square {
  aspect-ratio: 1 / 1;
}
</style>
