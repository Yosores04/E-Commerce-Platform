<template>
  <DefaultLayout>
    <div class="bg-whitesmoke min-h-screen">
      <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
          <p class="text-gray-600 mt-1">{{ cartStore.itemCount }} {{ cartStore.itemCount === 1 ? 'item' : 'items' }} in your cart</p>
        </div>
        
        <div v-if="cartStore.isLoading" class="flex justify-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-wine"></div>
        </div>

        <div v-else-if="cartStore.isEmpty" class="bg-white rounded-lg shadow-md text-center py-16 px-4">
          <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <h2 class="mt-6 text-2xl font-bold text-gray-900">Your cart is empty</h2>
          <p class="mt-2 text-gray-600">Looks like you haven't added any items yet</p>
          <router-link to="/products" class="mt-8 inline-flex items-center space-x-2 bg-wine text-white px-6 py-3 rounded-lg font-semibold hover:bg-wine/90 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Start Shopping</span>
          </router-link>
        </div>

        <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Cart Items -->
          <div class="lg:col-span-2 space-y-4">
            <div 
              v-for="item in cartStore.items" 
              :key="item.id" 
              class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow"
            >
              <div class="flex items-start space-x-4">
                <!-- Product Image -->
                <router-link 
                  :to="`/products/${item.product?.id}`"
                  class="flex-shrink-0 w-24 h-24 sm:w-32 sm:h-32 rounded-lg overflow-hidden bg-gray-100"
                >
                  <img 
                    :src="getProductImage(item.product)"
                    :alt="item.product?.name"
                    @error="handleImageError"
                    class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                  />
                </router-link>

                <!-- Product Details -->
                <div class="flex-1 min-w-0">
                  <router-link 
                    :to="`/products/${item.product?.id}`"
                    class="font-bold text-gray-900 hover:text-wine transition-colors text-lg block mb-1"
                  >
                    {{ item.product?.name }}
                  </router-link>
                  <p class="text-sm text-gray-600 mb-2">
                    <span class="font-medium">Vendor:</span> {{ item.product?.vendor?.shop_name || 'Unknown' }}
                  </p>
                  <p v-if="item.variant" class="text-sm text-gray-600 mb-2">
                    <span class="font-medium">Variant:</span> {{ item.variant.name }}
                  </p>
                  
                  <!-- Price -->
                  <div class="flex items-baseline space-x-2 mb-4">
                    <span class="text-2xl font-bold text-wine">₱{{ formatPrice(item.price) }}</span>
                    <span v-if="item.product?.compare_price && item.product.compare_price > item.price" 
                      class="text-sm text-gray-400 line-through">
                      ₱{{ formatPrice(item.product.compare_price) }}
                    </span>
                  </div>

                  <!-- Quantity Controls & Remove Button -->
                  <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                      <span class="text-sm text-gray-600 font-medium">Quantity:</span>
                      <div class="flex items-center border border-gray-300 rounded-lg">
                        <button 
                          @click="updateQuantity(item.id, item.quantity - 1)"
                          :disabled="item.quantity <= 1"
                          class="px-3 py-2 text-gray-600 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                          </svg>
                        </button>
                        <input 
                          :value="item.quantity"
                          type="number"
                          min="1"
                          :max="item.product?.stock || 999"
                          class="w-16 text-center border-x border-gray-300 py-2 focus:outline-none"
                          @change="(e) => updateQuantity(item.id, parseInt(e.target.value))"
                        />
                        <button 
                          @click="updateQuantity(item.id, item.quantity + 1)"
                          :disabled="item.quantity >= (item.product?.stock || 999)"
                          class="px-3 py-2 text-gray-600 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                          </svg>
                        </button>
                      </div>
                    </div>

                    <button 
                      @click="removeItem(item.id)"
                      class="text-red-600 hover:text-red-700 flex items-center space-x-1 transition-colors"
                      title="Remove item"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                      <span class="text-sm">Remove</span>
                    </button>
                  </div>

                  <!-- Stock Warning -->
                  <div v-if="item.product && item.product.stock < 5" class="mt-3">
                    <span class="text-sm text-orange-600 font-medium">
                      ⚠️ Only {{ item.product.stock }} left in stock
                    </span>
                  </div>

                  <!-- Subtotal -->
                  <div class="mt-3 pt-3 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                      <span class="text-sm text-gray-600">Item subtotal:</span>
                      <span class="text-lg font-bold text-gray-900">₱{{ formatPrice(item.price * item.quantity) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Order Summary -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
              <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Order Summary</h2>
              
              <div class="space-y-3 mb-6">
                <div class="flex justify-between text-gray-700">
                  <span>Subtotal ({{ cartStore.itemCount }} items)</span>
                  <span class="font-medium">₱{{ formatPrice(cartStore.subtotal) }}</span>
                </div>
                <div class="flex justify-between text-gray-700">
                  <span>Shipping</span>
                  <span class="font-medium">
                    {{ cartStore.shipping > 0 ? '₱' + formatPrice(cartStore.shipping) : 'Calculated at checkout' }}
                  </span>
                </div>
                <div class="flex justify-between text-gray-700">
                  <span>Tax (12%)</span>
                  <span class="font-medium">₱{{ formatPrice(cartStore.tax) }}</span>
                </div>
                <div v-if="cartStore.discount > 0" class="flex justify-between text-green-600">
                  <span class="flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span>Discount</span>
                  </span>
                  <span class="font-medium">-₱{{ formatPrice(cartStore.discount) }}</span>
                </div>
              </div>

              <div class="border-t border-gray-200 pt-4 mb-6">
                <div class="flex justify-between items-center">
                  <span class="text-lg font-bold text-gray-900">Total</span>
                  <span class="text-2xl font-bold text-wine">₱{{ formatPrice(cartStore.total) }}</span>
                </div>
              </div>

              <router-link 
                to="/checkout" 
                class="block w-full bg-wine text-white text-center py-3 px-6 rounded-lg font-semibold hover:bg-wine/90 transition-all shadow-md hover:shadow-lg mb-3"
              >
                <span class="flex items-center justify-center space-x-2">
                  <span>Proceed to Checkout</span>
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                  </svg>
                </span>
              </router-link>

              <router-link 
                to="/products" 
                class="block w-full border-2 border-wine text-wine text-center py-3 px-6 rounded-lg font-semibold hover:bg-wine hover:text-white transition-all"
              >
                Continue Shopping
              </router-link>

              <!-- Trust Badges -->
              <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                  <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                  </svg>
                  <span>Secure Checkout</span>
                </div>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  <span>Free shipping over ₱2,500</span>
                </div>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                  <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
                  <span>30-day return policy</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import DefaultLayout from '../layouts/DefaultLayout.vue'
import { useCartStore } from '../stores/cart'

const cartStore = useCartStore()
const imageError = ref(false)

onMounted(() => {
  cartStore.fetchCart()
})

const getProductImage = (product) => {
  if (!product) return 'https://via.placeholder.com/800x800/5B2333/F7F4F3?text=No+Image'
  
  if (product.images && product.images.length > 0) {
    const primary = product.images.find(img => img.is_primary)
    return primary ? primary.image_path : product.images[0].image_path
  }
  
  return 'https://via.placeholder.com/800x800/5B2333/F7F4F3?text=No+Image'
}

const handleImageError = (event) => {
  event.target.src = 'https://via.placeholder.com/800x800/5B2333/F7F4F3?text=Image+Not+Available'
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-PH').format(price)
}

const updateQuantity = async (itemId, newQuantity) => {
  if (newQuantity < 1) return
  
  try {
    await cartStore.updateItem(itemId, newQuantity)
  } catch (error) {
    console.error('Failed to update quantity:', error)
    alert('Failed to update quantity. Please try again.')
  }
}

const removeItem = async (itemId) => {
  if (confirm('Are you sure you want to remove this item from your cart?')) {
    try {
      await cartStore.removeItem(itemId)
    } catch (error) {
      console.error('Failed to remove item:', error)
      alert('Failed to remove item. Please try again.')
    }
  }
}
</script>

<style scoped>
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
