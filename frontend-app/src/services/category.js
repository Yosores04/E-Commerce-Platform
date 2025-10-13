import api from './api'

export const categoryService = {
  /**
   * Get all categories
   */
  async getCategories(params = {}) {
    const response = await api.get('/categories', { params })
    return response.data
  },

  /**
   * Get a single category by ID
   */
  async getCategory(id) {
    const response = await api.get(`/categories/${id}`)
    return response.data
  },

  /**
   * Get products in a category
   */
  async getCategoryProducts(id, params = {}) {
    const response = await api.get(`/categories/${id}/products`, { params })
    return response.data
  },

  /**
   * Admin: Create category
   */
  async createCategory(categoryData) {
    const response = await api.post('/admin/categories', categoryData)
    return response.data
  },

  /**
   * Admin: Update category
   */
  async updateCategory(id, categoryData) {
    const response = await api.put(`/admin/categories/${id}`, categoryData)
    return response.data
  },

  /**
   * Admin: Delete category
   */
  async deleteCategory(id) {
    const response = await api.delete(`/admin/categories/${id}`)
    return response.data
  }
}
