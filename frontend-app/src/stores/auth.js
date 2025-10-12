import { defineStore } from 'pinia'
import { authService } from '../services/auth'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    isLoading: false,
    error: null
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    
    currentUser: (state) => state.user,
    
    userRoles: (state) => state.user?.roles?.map(role => role.name) || [],
    
    hasRole: (state) => (roleName) => {
      return state.user?.roles?.some(role => role.name === roleName) || false
    },
    
    hasPermission: (state) => (permissionName) => {
      if (!state.user?.roles) return false
      
      return state.user.roles.some(role =>
        role.permissions?.some(permission => permission.name === permissionName)
      )
    }
  },

  actions: {
    async register(userData) {
      this.isLoading = true
      this.error = null
      
      try {
        const response = await authService.register(userData)
        this.token = response.data.token
        this.user = response.data.user
        
        localStorage.setItem('token', this.token)
        
        return response
      } catch (error) {
        this.error = error.response?.data?.message || 'Registration failed'
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async login(credentials) {
      this.isLoading = true
      this.error = null
      
      try {
        const response = await authService.login(credentials)
        this.token = response.data.token
        this.user = response.data.user
        
        localStorage.setItem('token', this.token)
        
        return response
      } catch (error) {
        this.error = error.response?.data?.message || 'Login failed'
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async logout() {
      try {
        await authService.logout()
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.token = null
        this.user = null
        localStorage.removeItem('token')
      }
    },

    async fetchProfile() {
      this.isLoading = true
      
      try {
        const response = await authService.getProfile()
        this.user = response.data
        return response
      } catch (error) {
        console.error('Failed to fetch profile:', error)
        // If token is invalid, clear local state without calling logout API
        if (error.response?.status === 401) {
          this.token = null
          this.user = null
          localStorage.removeItem('token')
        }
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async updateProfile(profileData) {
      this.isLoading = true
      this.error = null
      
      try {
        const response = await authService.updateProfile(profileData)
        this.user = response.data
        return response
      } catch (error) {
        this.error = error.response?.data?.message || 'Profile update failed'
        throw error
      } finally {
        this.isLoading = false
      }
    },

    async changePassword(passwordData) {
      this.isLoading = true
      this.error = null
      
      try {
        const response = await authService.changePassword(passwordData)
        return response
      } catch (error) {
        this.error = error.response?.data?.message || 'Password change failed'
        throw error
      } finally {
        this.isLoading = false
      }
    },

    // Initialize auth state from token
    async initAuth() {
      if (this.token && !this.user) {
        try {
          await this.fetchProfile()
        } catch (error) {
          console.error('Failed to initialize auth:', error)
          this.logout()
        }
      }
    }
  }
})
