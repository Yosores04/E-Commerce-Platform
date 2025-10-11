import api from './api'

export const cartService = {
  async getCart() {
    const response = await api.get('/cart')
    return response.data
  },

  async addItem(productId, quantity = 1, variantId = null) {
    const response = await api.post('/cart/items', {
      product_id: productId,
      quantity,
      variant_id: variantId
    })
    return response.data
  },

  async updateItem(itemId, quantity) {
    const response = await api.put(`/cart/items/${itemId}`, { quantity })
    return response.data
  },

  async removeItem(itemId) {
    const response = await api.delete(`/cart/items/${itemId}`)
    return response.data
  },

  async clearCart() {
    const response = await api.delete('/cart')
    return response.data
  },

  async applyCoupon(code) {
    const response = await api.post('/cart/coupon', { code })
    return response.data
  },

  async removeCoupon() {
    const response = await api.delete('/cart/coupon')
    return response.data
  }
}
