import api from './api'

export const orderService = {
  /**
   * Get user's orders
   */
  async getOrders(params = {}) {
    const response = await api.get('/orders', { params })
    return response.data
  },

  /**
   * Get single order
   */
  async getOrder(orderId) {
    const response = await api.get(`/orders/${orderId}`)
    return response.data
  },

  /**
   * Create order from cart
   */
  async createOrder(orderData) {
    const response = await api.post('/orders', orderData)
    return response.data
  },

  /**
   * Cancel order
   */
  async cancelOrder(orderId, reason = '') {
    const response = await api.post(`/orders/${orderId}/cancel`, { reason })
    return response.data
  },

  /**
   * Track order
   */
  async trackOrder(orderNumber) {
    const response = await api.get(`/orders/track/${orderNumber}`)
    return response.data
  },

  /**
   * Rate order
   */
  async rateOrder(orderId, rating, review = '') {
    const response = await api.post(`/orders/${orderId}/rate`, { rating, review })
    return response.data
  }
}
