<template>
  <div class="min-h-screen bg-white-smoke">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-xerxia-wine">Vendors & Users Management</h1>
            <p class="text-gray-600 mt-1">Manage vendors, customers, and user accounts</p>
          </div>
          <button
            @click="showAddModal = true"
            class="px-6 py-3 bg-xerxia-wine text-white rounded-lg hover:bg-burgundy transition-colors font-medium flex items-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add User
          </button>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 mb-1">Total Users</p>
              <p class="text-3xl font-bold text-xerxia-wine">{{ users.length }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 mb-1">Active Vendors</p>
              <p class="text-3xl font-bold text-green-600">{{ vendorStats.active }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 mb-1">Pending Approval</p>
              <p class="text-3xl font-bold text-yellow-600">{{ vendorStats.pending }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
              <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 mb-1">Customers</p>
              <p class="text-3xl font-bold text-blue-600">{{ customerCount }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="mb-6">
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex space-x-8">
            <button
              @click="activeTab = 'all'"
              :class="activeTab === 'all' 
                ? 'border-xerxia-wine text-xerxia-wine' 
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
              All Users
            </button>
            <button
              @click="activeTab = 'vendors'"
              :class="activeTab === 'vendors' 
                ? 'border-xerxia-wine text-xerxia-wine' 
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
              Vendors
            </button>
            <button
              @click="activeTab = 'customers'"
              :class="activeTab === 'customers' 
                ? 'border-xerxia-wine text-xerxia-wine' 
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
              Customers
            </button>
            <button
              @click="activeTab = 'pending'"
              :class="activeTab === 'pending' 
                ? 'border-xerxia-wine text-xerxia-wine' 
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2"
            >
              Pending Approval
              <span v-if="vendorStats.pending" class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full text-xs font-semibold">
                {{ vendorStats.pending }}
              </span>
            </button>
          </nav>
        </div>
      </div>

      <!-- Search and Filter -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
          <div class="flex-1">
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Search users..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
            />
          </div>
          <select
            v-model="filterStatus"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
          >
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="suspended">Suspended</option>
          </select>
        </div>
      </div>

      <!-- Users Table -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                User
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Role
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Joined Date
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="user in filteredUsers"
              :key="user.id"
              class="hover:bg-gray-50"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-xerxia-wine flex items-center justify-center text-white font-semibold">
                      {{ user.name.charAt(0) }}
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                    <div class="text-sm text-gray-500">{{ user.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="getRoleBadgeClass(user.role)"
                  class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                >
                  {{ user.role }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatDate(user.joined_date) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="getStatusClass(user.status)"
                  class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                >
                  {{ user.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  v-if="user.role === 'vendor' && user.status === 'pending'"
                  @click="approveVendor(user.id)"
                  class="text-green-600 hover:text-green-900 mr-3"
                >
                  Approve
                </button>
                <button
                  @click="editUser(user)"
                  class="text-xerxia-wine hover:text-burgundy mr-3"
                >
                  Edit
                </button>
                <button
                  @click="suspendUser(user.id)"
                  class="text-red-600 hover:text-red-900"
                >
                  {{ user.status === 'suspended' ? 'Activate' : 'Suspend' }}
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-6 flex items-center justify-between">
        <p class="text-sm text-gray-600">
          Showing {{ filteredUsers.length }} of {{ users.length }} users
        </p>
        <div class="flex gap-2">
          <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
            Previous
          </button>
          <button class="px-4 py-2 bg-xerxia-wine text-white rounded-lg">
            1
          </button>
          <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
            2
          </button>
          <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
            Next
          </button>
        </div>
      </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div
      v-if="showAddModal || showEditModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closeModal"
    >
      <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6">
          <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">
              {{ showEditModal ? 'Edit User' : 'Add New User' }}
            </h3>
            <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <form @submit.prevent="saveUser" class="p-6 space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Full Name *
              </label>
              <input
                type="text"
                v-model="formData.name"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
              />
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Email *
              </label>
              <input
                type="email"
                v-model="formData.email"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Role *
              </label>
              <select
                v-model="formData.role"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
              >
                <option value="customer">Customer</option>
                <option value="vendor">Vendor</option>
                <option value="admin">Admin</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Status *
              </label>
              <select
                v-model="formData.status"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="pending">Pending</option>
                <option value="suspended">Suspended</option>
              </select>
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Phone
              </label>
              <input
                type="tel"
                v-model="formData.phone"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
              />
            </div>

            <div class="md:col-span-2" v-if="!showEditModal">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Password *
              </label>
              <input
                type="password"
                v-model="formData.password"
                :required="!showEditModal"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
              />
            </div>
          </div>

          <div class="flex gap-3 pt-4">
            <button
              type="button"
              @click="closeModal"
              class="flex-1 px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="flex-1 px-6 py-2 bg-xerxia-wine text-white rounded-lg hover:bg-burgundy transition-colors font-medium"
            >
              {{ showEditModal ? 'Update User' : 'Add User' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// State
const activeTab = ref('all')
const searchQuery = ref('')
const filterStatus = ref('')

// Modals
const showAddModal = ref(false)
const showEditModal = ref(false)

// Form data
const formData = ref({
  name: '',
  email: '',
  role: 'customer',
  status: 'active',
  phone: '',
  password: ''
})

// Mock users data
const users = ref([
  {
    id: 1,
    name: 'Juan Dela Cruz',
    email: 'juan@example.com',
    role: 'vendor',
    status: 'active',
    joined_date: '2024-08-15',
    phone: '+63 912 345 6789'
  },
  {
    id: 2,
    name: 'Maria Santos',
    email: 'maria@example.com',
    role: 'customer',
    status: 'active',
    joined_date: '2024-09-20',
    phone: '+63 923 456 7890'
  },
  {
    id: 3,
    name: 'Pedro Reyes',
    email: 'pedro@example.com',
    role: 'vendor',
    status: 'pending',
    joined_date: '2024-10-01',
    phone: '+63 934 567 8901'
  },
  {
    id: 4,
    name: 'Ana Garcia',
    email: 'ana@example.com',
    role: 'customer',
    status: 'active',
    joined_date: '2024-09-10',
    phone: '+63 945 678 9012'
  },
  {
    id: 5,
    name: 'Carlos Lopez',
    email: 'carlos@example.com',
    role: 'vendor',
    status: 'active',
    joined_date: '2024-07-25',
    phone: '+63 956 789 0123'
  },
  {
    id: 6,
    name: 'Rosa Martinez',
    email: 'rosa@example.com',
    role: 'customer',
    status: 'active',
    joined_date: '2024-10-05',
    phone: '+63 967 890 1234'
  },
  {
    id: 7,
    name: 'Miguel Torres',
    email: 'miguel@example.com',
    role: 'vendor',
    status: 'pending',
    joined_date: '2024-10-12',
    phone: '+63 978 901 2345'
  },
  {
    id: 8,
    name: 'Admin User',
    email: 'admin@xerxia.com',
    role: 'admin',
    status: 'active',
    joined_date: '2024-01-01',
    phone: '+63 900 000 0000'
  }
])

// Computed
const vendorStats = computed(() => {
  const vendors = users.value.filter(u => u.role === 'vendor')
  return {
    active: vendors.filter(v => v.status === 'active').length,
    pending: vendors.filter(v => v.status === 'pending').length
  }
})

const customerCount = computed(() => {
  return users.value.filter(u => u.role === 'customer').length
})

const filteredUsers = computed(() => {
  return users.value.filter(user => {
    // Tab filter
    let matchesTab = true
    if (activeTab.value === 'vendors') {
      matchesTab = user.role === 'vendor'
    } else if (activeTab.value === 'customers') {
      matchesTab = user.role === 'customer'
    } else if (activeTab.value === 'pending') {
      matchesTab = user.role === 'vendor' && user.status === 'pending'
    }

    // Search filter
    const matchesSearch = user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                          user.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    
    // Status filter
    const matchesStatus = !filterStatus.value || user.status === filterStatus.value
    
    return matchesTab && matchesSearch && matchesStatus
  })
})

const editUser = (user) => {
  formData.value = { ...user }
  showEditModal.value = true
}

const approveVendor = (userId) => {
  const user = users.value.find(u => u.id === userId)
  if (user) {
    user.status = 'active'
  }
}

const suspendUser = (userId) => {
  const user = users.value.find(u => u.id === userId)
  if (user) {
    user.status = user.status === 'suspended' ? 'active' : 'suspended'
  }
}

const saveUser = () => {
  if (showEditModal.value) {
    const index = users.value.findIndex(u => u.id === formData.value.id)
    if (index !== -1) {
      users.value[index] = { ...formData.value }
    }
  } else {
    const newUser = {
      ...formData.value,
      id: Date.now(),
      joined_date: new Date().toISOString().split('T')[0]
    }
    delete newUser.password
    users.value.push(newUser)
  }
  closeModal()
}

const closeModal = () => {
  showAddModal.value = false
  showEditModal.value = false
  formData.value = {
    name: '',
    email: '',
    role: 'customer',
    status: 'active',
    phone: '',
    password: ''
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-PH', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const getRoleBadgeClass = (role) => {
  const classes = {
    'admin': 'bg-red-100 text-red-800',
    'vendor': 'bg-purple-100 text-purple-800',
    'customer': 'bg-blue-100 text-blue-800'
  }
  return classes[role] || 'bg-gray-100 text-gray-800'
}

const getStatusClass = (status) => {
  const classes = {
    'active': 'bg-green-100 text-green-800',
    'inactive': 'bg-gray-100 text-gray-800',
    'pending': 'bg-yellow-100 text-yellow-800',
    'suspended': 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}
</script>
