<template>
  <div class="product-card cursor-pointer" @click="goToProduct">
    <!-- Product Image -->
    <div class="relative">
      <img 
        :src="product.image_url || '/placeholder-product.jpg'" 
        :alt="product.name"
        class="product-card-image"
      />
      <span 
        v-if="product.stock <= 5 && product.stock > 0"
        class="absolute top-2 right-2 badge badge-warning"
      >
        Low Stock
      </span>
      <span 
        v-if="product.stock === 0"
        class="absolute top-2 right-2 badge badge-danger"
      >
        Out of Stock
      </span>
    </div>

    <!-- Product Info -->
    <div class="p-4">
      <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
        {{ product.name }}
      </h3>
      
      <p class="text-sm text-gray-600 mb-2 line-clamp-2">
        {{ product.description }}
      </p>

      <div class="flex items-center justify-between mb-3">
        <div>
          <p class="text-2xl font-bold text-primary-600">
            Rp {{ formatPrice(product.price) }}
          </p>
          <p v-if="product.compare_price" class="text-sm text-gray-500 line-through">
            Rp {{ formatPrice(product.compare_price) }}
          </p>
        </div>
        <div v-if="product.rating" class="flex items-center">
          <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
          <span class="ml-1 text-sm text-gray-600">{{ product.rating }}</span>
        </div>
      </div>

      <div class="text-sm text-gray-600 mb-3">
        <p>by {{ product.vendor?.shop_name || 'Unknown' }}</p>
      </div>

      <button 
        @click.stop="addToCart"
        :disabled="product.stock === 0"
        class="w-full btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <span v-if="product.stock === 0">Out of Stock</span>
        <span v-else>Add to Cart</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useCartStore } from '../stores/cart'

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
})

const router = useRouter()
const cartStore = useCartStore()

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
    alert('Product added to cart!')
  } catch (error) {
    console.error('Failed to add to cart:', error)
    alert('Failed to add product to cart')
  }
}
</script>

<style scoped>
.product-card-image {
  @apply w-full h-64 object-cover rounded-t-lg;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
