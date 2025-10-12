import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('../views/HomePage.vue')
  },
  {
    path: '/products',
    name: 'Products',
    component: () => import('../views/ProductsPage.vue')
  },
  {
    path: '/products/:id',
    name: 'ProductDetail',
    component: () => import('../views/ProductDetailPage.vue')
  },
  {
    path: '/cart',
    name: 'Cart',
    component: () => import('../views/CartPage.vue')
  },
  {
    path: '/checkout',
    name: 'Checkout',
    component: () => import('../views/CheckoutPage.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/orders',
    name: 'Orders',
    component: () => import('../views/OrdersPage.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/orders/:id',
    name: 'OrderDetail',
    component: () => import('../views/OrderDetailPage.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/LoginPage.vue'),
    meta: { requiresGuest: true }
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/RegisterPage.vue'),
    meta: { requiresGuest: true }
  },
  {
    path: '/vendor/dashboard',
    name: 'VendorDashboard',
    component: () => import('../views/vendor/DashboardPage.vue'),
    meta: { requiresAuth: true, requiresVendor: true }
  },
  {
    path: '/vendor/products',
    name: 'VendorProducts',
    component: () => import('../views/vendor/ProductsPage.vue'),
    meta: { requiresAuth: true, requiresVendor: true }
  },
  {
    path: '/vendor/orders',
    name: 'VendorOrders',
    component: () => import('../views/vendor/OrdersPage.vue'),
    meta: { requiresAuth: true, requiresVendor: true }
  },
  {
    path: '/admin/dashboard',
    name: 'AdminDashboard',
    component: () => import('../views/admin/DashboardPage.vue'),
    meta: { requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/admin/vendors',
    name: 'AdminVendors',
    component: () => import('../views/admin/VendorsPage.vue'),
    meta: { requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/admin/categories',
    name: 'AdminCategories',
    component: () => import('../views/admin/CategoriesPage.vue'),
    meta: { requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('../views/NotFoundPage.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0 }
    }
  }
})

// Navigation guards
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  
  // If user has a token but no user data, try to fetch it
  if (authStore.token && !authStore.user && !to.meta.requiresGuest) {
    try {
      await authStore.fetchProfile()
    } catch (error) {
      // If profile fetch fails, clear invalid token
      authStore.logout()
    }
  }
  
  // Check if route requires authentication
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'Login', query: { redirect: to.fullPath } })
    return
  }
  
  // Check if route requires guest (not authenticated)
  if (to.meta.requiresGuest && authStore.isAuthenticated) {
    next({ name: 'Home' })
    return
  }
  
  // Check if route requires vendor role
  if (to.meta.requiresVendor && !authStore.hasRole('vendor')) {
    next({ name: 'Home' })
    return
  }
  
  // Check if route requires admin role
  if (to.meta.requiresAdmin && !authStore.hasRole('admin')) {
    next({ name: 'Home' })
    return
  }
  
  next()
})

export default router
