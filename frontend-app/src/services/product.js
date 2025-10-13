import api from './api'

export const productService = {
  /**
   * Get all products with filters and pagination
   */
  async getProducts(params = {}) {
    const response = await api.get('/products', { params })
    return response.data
  },

  /**
   * Get a single product by ID
   */
  async getProduct(id) {
    const response = await api.get(`/products/${id}`)
    return response.data
  },

  /**
   * Get related products
   */
  async getRelatedProducts(id, limit = 4) {
    const response = await api.get(`/products/${id}/related`, {
      params: { limit }
    })
    return response.data
  },

  /**
   * Get featured products
   */
  async getFeaturedProducts(limit = 8) {
    const response = await api.get('/products', {
      params: {
        featured: true,
        per_page: limit
      }
    })
    return response.data
  },

  /**
   * Search products
   */
  async searchProducts(query, params = {}) {
    const response = await api.get('/products', {
      params: {
        search: query,
        ...params
      }
    })
    return response.data
  },

  /**
   * Filter products by category
   */
  async getProductsByCategory(categoryId, params = {}) {
    const response = await api.get('/products', {
      params: {
        category_id: categoryId,
        ...params
      }
    })
    return response.data
  },

  /**
   * Filter products by vendor
   */
  async getProductsByVendor(vendorId, params = {}) {
    const response = await api.get('/products', {
      params: {
        vendor_id: vendorId,
        ...params
      }
    })
    return response.data
  },

  /**
   * Admin: Create product
   */
  async createProduct(productData) {
    const response = await api.post('/admin/products', productData)
    return response.data
  },

  /**
   * Admin: Update product
   */
  async updateProduct(id, productData) {
    const response = await api.put(`/admin/products/${id}`, productData)
    return response.data
  },

  /**
   * Admin: Delete product
   */
  async deleteProduct(id) {
    const response = await api.delete(`/admin/products/${id}`)
    return response.data
  },

  /**
   * Vendor: Get vendor's products
   */
  async getVendorProducts(params = {}) {
    const response = await api.get('/vendor/products', { params })
    return response.data
  },

  /**
   * Vendor: Create product
   */
  async createVendorProduct(productData) {
    const response = await api.post('/vendor/products', productData)
    return response.data
  },

  /**
   * Vendor: Update product
   */
  async updateVendorProduct(id, productData) {
    const response = await api.put(`/vendor/products/${id}`, productData)
    return response.data
  }
}
