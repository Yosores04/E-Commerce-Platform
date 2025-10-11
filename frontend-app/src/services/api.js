import axios from 'axios'
import { useAuthStore } from '../stores/auth'

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Request interceptor to add auth token
api.interceptors.request.use(
  (config) => {
    const authStore = useAuthStore()
    const token = authStore.token
    
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Response interceptor to handle errors
api.interceptors.response.use(
  (response) => {
    return response
  },
  (error) => {
    if (error.response) {
      // Handle specific error codes
      switch (error.response.status) {
        case 401:
          // Unauthorized - clear token and redirect to login
          const authStore = useAuthStore()
          authStore.logout()
          window.location.href = '/login'
          break
        case 403:
          // Forbidden
          console.error('Access denied')
          break
        case 404:
          // Not found
          console.error('Resource not found')
          break
        case 422:
          // Validation errors
          console.error('Validation errors:', error.response.data.errors)
          break
        case 500:
          // Server error
          console.error('Server error')
          break
      }
    } else if (error.request) {
      // Request made but no response
      console.error('Network error - no response from server')
    } else {
      // Something else happened
      console.error('Error:', error.message)
    }
    
    return Promise.reject(error)
  }
)

export default api
