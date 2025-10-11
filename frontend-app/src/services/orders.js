import api from './api'

export const ordersService = {
  async getOrders(params = {}) {
    const response = await api.get('/orders', { params })
    return response.data
  },

  async getOrder(id) {
    const response = await api.get(`/orders/${id}`)
    return response.data
  },

  async createOrder(orderData) {
    const response = await api.post('/orders', orderData)
    return response.data
  },

  async cancelOrder(id) {
    const response = await api.post(`/orders/${id}/cancel`)
    return response.data
  },

  // Vendor endpoints
  async getVendorOrders(params = {}) {
    const response = await api.get('/vendor/orders', { params })
    return response.data
  },

  async updateOrderStatus(orderId, status) {
    const response = await api.put(`/vendor/orders/${orderId}/status`, { status })
    return response.data
  },

  async addTrackingNumber(orderId, trackingNumber) {
    const response = await api.put(`/vendor/orders/${orderId}/tracking`, {
      tracking_number: trackingNumber
    })
    return response.data
  }
}
