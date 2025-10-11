import api from './api'

export const productsService = {
  async getProducts(params = {}) {
    const response = await api.get('/products', { params })
    return response.data
  },

  async getProduct(id) {
    const response = await api.get(`/products/${id}`)
    return response.data
  },

  async getCategories() {
    const response = await api.get('/categories')
    return response.data
  },

  async getCategory(id) {
    const response = await api.get(`/categories/${id}`)
    return response.data
  },

  async searchProducts(query, params = {}) {
    const response = await api.get('/products/search', {
      params: { q: query, ...params }
    })
    return response.data
  },

  // Vendor endpoints
  async createProduct(productData) {
    const response = await api.post('/vendor/products', productData)
    return response.data
  },

  async updateProduct(id, productData) {
    const response = await api.put(`/vendor/products/${id}`, productData)
    return response.data
  },

  async deleteProduct(id) {
    const response = await api.delete(`/vendor/products/${id}`)
    return response.data
  },

  async uploadProductImage(productId, formData) {
    const response = await api.post(`/vendor/products/${productId}/images`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    return response.data
  }
}
