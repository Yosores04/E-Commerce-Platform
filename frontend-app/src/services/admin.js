import api from './api'

export const adminService = {
  /**
   * Get dashboard statistics
   */
  async getDashboardStats() {
    const response = await api.get('/admin/dashboard/stats')
    return response.data
  },

  /**
   * Get all users
   */
  async getUsers(params = {}) {
    const response = await api.get('/admin/users', { params })
    return response.data
  },

  /**
   * Get all vendors
   */
  async getVendors(params = {}) {
    const response = await api.get('/admin/vendors', { params })
    return response.data
  },

  /**
   * Approve vendor
   */
  async approveVendor(vendorId) {
    const response = await api.post(`/admin/vendors/${vendorId}/approve`)
    return response.data
  },

  /**
   * Reject vendor
   */
  async rejectVendor(vendorId) {
    const response = await api.post(`/admin/vendors/${vendorId}/reject`)
    return response.data
  },

  /**
   * Suspend vendor
   */
  async suspendVendor(vendorId) {
    const response = await api.post(`/admin/vendors/${vendorId}/suspend`)
    return response.data
  },

  /**
   * Get all orders
   */
  async getOrders(params = {}) {
    const response = await api.get('/admin/orders', { params })
    return response.data
  },

  /**
   * Update order status
   */
  async updateOrderStatus(orderId, status) {
    const response = await api.put(`/admin/orders/${orderId}/status`, { status })
    return response.data
  }
}
