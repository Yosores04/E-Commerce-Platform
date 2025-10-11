<template>
  <DefaultLayout>
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-6">Shopping Cart</h1>
      
      <div v-if="cartStore.isLoading" class="flex justify-center py-12">
        <div class="spinner"></div>
      </div>

      <div v-else-if="cartStore.isEmpty" class="text-center py-12">
        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <h2 class="mt-4 text-2xl font-semibold text-gray-900">Your cart is empty</h2>
        <p class="mt-2 text-gray-600">Start shopping to add items to your cart</p>
        <router-link to="/products" class="mt-6 inline-block btn-primary">
          Continue Shopping
        </router-link>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-lg shadow-md p-6">
            <div v-for="item in cartStore.items" :key="item.id" class="flex items-center py-4 border-b last:border-b-0">
              <img 
                :src="item.product?.image_url || '/placeholder-product.jpg'" 
                :alt="item.product?.name"
                class="w-24 h-24 object-cover rounded-md"
              />
              <div class="flex-1 ml-4">
                <h3 class="font-semibold text-gray-900">{{ item.product?.name }}</h3>
                <p class="text-sm text-gray-600">{{ item.product?.vendor?.shop_name }}</p>
                <p class="text-lg font-bold text-primary-600 mt-2">
                  Rp {{ formatPrice(item.price) }}
                </p>
              </div>
              <div class="flex items-center space-x-2">
                <button 
                  @click="updateQuantity(item.id, item.quantity - 1)"
                  class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-md hover:bg-gray-50"
                >
                  -
                </button>
                <span class="w-12 text-center">{{ item.quantity }}</span>
                <button 
                  @click="updateQuantity(item.id, item.quantity + 1)"
                  class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-md hover:bg-gray-50"
                >
                  +
                </button>
              </div>
              <button 
                @click="removeItem(item.id)"
                class="ml-4 text-red-600 hover:text-red-700"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h2>
            <div class="space-y-3 mb-4">
              <div class="flex justify-between text-gray-700">
                <span>Subtotal</span>
                <span>Rp {{ formatPrice(cartStore.subtotal) }}</span>
              </div>
              <div class="flex justify-between text-gray-700">
                <span>Shipping</span>
                <span>Rp {{ formatPrice(cartStore.shipping) }}</span>
              </div>
              <div class="flex justify-between text-gray-700">
                <span>Tax</span>
                <span>Rp {{ formatPrice(cartStore.tax) }}</span>
              </div>
              <div v-if="cartStore.discount > 0" class="flex justify-between text-green-600">
                <span>Discount</span>
                <span>-Rp {{ formatPrice(cartStore.discount) }}</span>
              </div>
              <div class="border-t pt-3 flex justify-between text-xl font-bold text-gray-900">
                <span>Total</span>
                <span>Rp {{ formatPrice(cartStore.total) }}</span>
              </div>
            </div>
            <router-link to="/checkout" class="block w-full btn-primary text-center">
              Proceed to Checkout
            </router-link>
            <router-link to="/products" class="block w-full mt-3 btn-secondary text-center">
              Continue Shopping
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { onMounted } from 'vue'
import DefaultLayout from '../layouts/DefaultLayout.vue'
import { useCartStore } from '../stores/cart'

const cartStore = useCartStore()

onMounted(() => {
  cartStore.fetchCart()
})

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(price)
}

const updateQuantity = async (itemId, newQuantity) => {
  if (newQuantity < 1) return
  try {
    await cartStore.updateItem(itemId, newQuantity)
  } catch (error) {
    console.error('Failed to update quantity:', error)
  }
}

const removeItem = async (itemId) => {
  if (confirm('Remove this item from cart?')) {
    try {
      await cartStore.removeItem(itemId)
    } catch (error) {
      console.error('Failed to remove item:', error)
    }
  }
}
</script>
