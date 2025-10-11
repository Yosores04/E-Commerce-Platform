import { defineStore } from 'pinia'
import { cartService } from '../services/cart'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
    total: 0,
    subtotal: 0,
    tax: 0,
    shipping: 0,
    discount: 0,
    coupon: null,
    isLoading: false,
    error: null
  }),

  getters: {
    itemCount: (state) => state.items.reduce((count, item) => count + item.quantity, 0),
    
    isEmpty: (state) => state.items.length === 0,
    
    hasItems: (state) => state.items.length > 0,
    
    getItemByProductId: (state) => (productId) => {
      return state.items.find(item => item.product_id === productId)
    }
  },

  actions: {
    async fetchCart() {
      this.isLoading = true
      this.error = null
      
      try {
        const response = await cartService.getCart()
        this.items = response.data.items || []
        this.total = response.data.total || 0
        this.subtotal = response.data.subtotal || 0
        this.tax = response.data.tax || 0
        this.shipping = response.data.shipping || 0
        this.discount = response.data.discount || 0
        this.coupon = response.data.coupon || null
        
        return response
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch cart'
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async addItem(productId, quantity = 1, variantId = null) {
      this.isLoading = true
      this.error = null
      
      try {
        const response = await cartService.addItem(productId, quantity, variantId)
        await this.fetchCart()
        return response
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to add item to cart'
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async updateItem(itemId, quantity) {
      this.isLoading = true
      this.error = null
      
      try {
        const response = await cartService.updateItem(itemId, quantity)
        await this.fetchCart()
        return response
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to update item'
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async removeItem(itemId) {
      this.isLoading = true
      this.error = null
      
      try {
        const response = await cartService.removeItem(itemId)
        await this.fetchCart()
        return response
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to remove item'
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async clearCart() {
      this.isLoading = true
      this.error = null
      
      try {
        const response = await cartService.clearCart()
        this.items = []
        this.total = 0
        this.subtotal = 0
        this.tax = 0
        this.shipping = 0
        this.discount = 0
        this.coupon = null
        return response
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to clear cart'
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async applyCoupon(code) {
      this.isLoading = true
      this.error = null
      
      try {
        const response = await cartService.applyCoupon(code)
        await this.fetchCart()
        return response
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to apply coupon'
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async removeCoupon() {
      this.isLoading = true
      this.error = null
      
      try {
        const response = await cartService.removeCoupon()
        await this.fetchCart()
        return response
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to remove coupon'
        throw error
      } finally {
        this.isLoading = false
      }
    }
  }
})
