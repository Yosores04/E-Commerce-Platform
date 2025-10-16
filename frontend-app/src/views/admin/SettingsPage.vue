<template>
  <AdminLayout>
    <div class="p-8">
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-3xl font-bold text-xerxia-wine">Settings</h1>
        <p class="text-gray-600 mt-1">Manage your store configuration and preferences</p>
      </div>
      <!-- Tabs -->
      <div class="mb-6">
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex space-x-8">
            <button
              @click="activeTab = 'general'"
              :class="activeTab === 'general' 
                ? 'border-xerxia-wine text-xerxia-wine' 
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
              General
            </button>
            <button
              @click="activeTab = 'payment'"
              :class="activeTab === 'payment' 
                ? 'border-xerxia-wine text-xerxia-wine' 
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
              Payment
            </button>
            <button
              @click="activeTab = 'shipping'"
              :class="activeTab === 'shipping' 
                ? 'border-xerxia-wine text-xerxia-wine' 
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
              Shipping
            </button>
            <button
              @click="activeTab = 'email'"
              :class="activeTab === 'email' 
                ? 'border-xerxia-wine text-xerxia-wine' 
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
              Email
            </button>
            <button
              @click="activeTab = 'notifications'"
              :class="activeTab === 'notifications' 
                ? 'border-xerxia-wine text-xerxia-wine' 
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
              Notifications
            </button>
          </nav>
        </div>
      </div>

      <!-- General Settings -->
      <div v-if="activeTab === 'general'" class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Store Information</h2>
          <form @submit.prevent="saveSettings" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Store Name</label>
                <input
                  type="text"
                  v-model="generalSettings.storeName"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
                />
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Store Description</label>
                <textarea
                  v-model="generalSettings.storeDescription"
                  rows="3"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
                ></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                <input
                  type="email"
                  v-model="generalSettings.contactEmail"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                <input
                  type="tel"
                  v-model="generalSettings.contactPhone"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
                />
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Store Address</label>
                <input
                  type="text"
                  v-model="generalSettings.address"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                <select
                  v-model="generalSettings.currency"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
                >
                  <option value="PHP">Philippine Peso (₱)</option>
                  <option value="USD">US Dollar ($)</option>
                  <option value="EUR">Euro (€)</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                <select
                  v-model="generalSettings.timezone"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
                >
                  <option value="Asia/Manila">Asia/Manila</option>
                  <option value="UTC">UTC</option>
                  <option value="America/New_York">America/New_York</option>
                </select>
              </div>
            </div>

            <div class="flex justify-end pt-4">
              <button
                type="submit"
                class="px-6 py-2 bg-xerxia-wine text-white rounded-lg hover:bg-burgundy transition-colors font-medium"
              >
                Save Changes
              </button>
            </div>
          </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Maintenance Mode</h2>
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-900 font-medium">Enable Maintenance Mode</p>
              <p class="text-sm text-gray-500 mt-1">Put your store in maintenance mode for updates</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
              <input type="checkbox" v-model="generalSettings.maintenanceMode" class="sr-only peer">
              <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-xerxia-wine/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-xerxia-wine"></div>
            </label>
          </div>
        </div>
      </div>

      <!-- Payment Settings -->
      <div v-if="activeTab === 'payment'" class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Payment Methods</h2>
          <div class="space-y-4">
            <div
              v-for="method in paymentMethods"
              :key="method.id"
              class="flex items-center justify-between p-4 border border-gray-200 rounded-lg"
            >
              <div class="flex items-center gap-4">
                <div :class="`w-12 h-12 rounded-lg ${method.color} flex items-center justify-center text-white`">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                  </svg>
                </div>
                <div>
                  <p class="font-medium text-gray-900">{{ method.name }}</p>
                  <p class="text-sm text-gray-500">{{ method.description }}</p>
                </div>
              </div>
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" v-model="method.enabled" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-xerxia-wine/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-xerxia-wine"></div>
              </label>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Tax Settings</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%)</label>
              <input
                type="number"
                v-model="paymentSettings.taxRate"
                step="0.01"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
              />
            </div>
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-900 font-medium">Include Tax in Prices</p>
                <p class="text-sm text-gray-500 mt-1">Display prices with tax included</p>
              </div>
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" v-model="paymentSettings.taxIncluded" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-xerxia-wine/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-xerxia-wine"></div>
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Shipping Settings -->
      <div v-if="activeTab === 'shipping'" class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Shipping Zones</h2>
          <div class="space-y-4">
            <div
              v-for="zone in shippingZones"
              :key="zone.id"
              class="p-4 border border-gray-200 rounded-lg"
            >
              <div class="flex items-start justify-between mb-3">
                <div>
                  <p class="font-medium text-gray-900">{{ zone.name }}</p>
                  <p class="text-sm text-gray-500">{{ zone.areas }}</p>
                </div>
                <button
                  @click="editZone(zone)"
                  class="text-sm text-xerxia-wine hover:text-burgundy font-medium"
                >
                  Edit
                </button>
              </div>
              <div class="flex items-center gap-2 text-sm">
                <span class="text-gray-600">Rate:</span>
                <span class="font-semibold text-gray-900">₱{{ formatNumber(zone.rate) }}</span>
                <span class="text-gray-400">•</span>
                <span class="text-gray-600">Est. {{ zone.estimatedDays }}</span>
              </div>
            </div>
          </div>
          <button class="mt-4 w-full px-4 py-2 border-2 border-xerxia-wine text-xerxia-wine rounded-lg hover:bg-xerxia-wine hover:text-white transition-colors font-medium">
            Add Shipping Zone
          </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Free Shipping</h2>
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-900 font-medium">Enable Free Shipping</p>
                <p class="text-sm text-gray-500 mt-1">Offer free shipping on orders</p>
              </div>
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" v-model="shippingSettings.freeShippingEnabled" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-xerxia-wine/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-xerxia-wine"></div>
              </label>
            </div>
            <div v-if="shippingSettings.freeShippingEnabled">
              <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Order Amount (₱)</label>
              <input
                type="number"
                v-model="shippingSettings.freeShippingMin"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Email Settings -->
      <div v-if="activeTab === 'email'" class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Email Configuration</h2>
          <form @submit.prevent="saveSettings" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">From Email</label>
                <input
                  type="email"
                  v-model="emailSettings.fromEmail"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
                />
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                <input
                  type="text"
                  v-model="emailSettings.fromName"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-xerxia-wine focus:border-xerxia-wine"
                />
              </div>
            </div>

            <div class="flex justify-end pt-4">
              <button
                type="submit"
                class="px-6 py-2 bg-xerxia-wine text-white rounded-lg hover:bg-burgundy transition-colors font-medium"
              >
                Save Changes
              </button>
            </div>
          </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Email Templates</h2>
          <div class="space-y-3">
            <div
              v-for="template in emailTemplates"
              :key="template.id"
              class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-xerxia-wine transition-colors"
            >
              <div>
                <p class="font-medium text-gray-900">{{ template.name }}</p>
                <p class="text-sm text-gray-500">{{ template.description }}</p>
              </div>
              <button class="text-sm text-xerxia-wine hover:text-burgundy font-medium">
                Edit Template
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Notifications Settings -->
      <div v-if="activeTab === 'notifications'" class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Admin Notifications</h2>
          <div class="space-y-4">
            <div
              v-for="notification in adminNotifications"
              :key="notification.id"
              class="flex items-center justify-between p-4 border border-gray-200 rounded-lg"
            >
              <div>
                <p class="font-medium text-gray-900">{{ notification.name }}</p>
                <p class="text-sm text-gray-500">{{ notification.description }}</p>
              </div>
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" v-model="notification.enabled" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-xerxia-wine/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-xerxia-wine"></div>
              </label>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Customer Notifications</h2>
          <div class="space-y-4">
            <div
              v-for="notification in customerNotifications"
              :key="notification.id"
              class="flex items-center justify-between p-4 border border-gray-200 rounded-lg"
            >
              <div>
                <p class="font-medium text-gray-900">{{ notification.name }}</p>
                <p class="text-sm text-gray-500">{{ notification.description }}</p>
              </div>
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" v-model="notification.enabled" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-xerxia-wine/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-xerxia-wine"></div>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import AdminLayout from '../../layouts/AdminLayout.vue'

const activeTab = ref('general')

// General Settings
const generalSettings = ref({
  storeName: 'Xerxia Wine Marketplace',
  storeDescription: 'Premium wine collection from around the world',
  contactEmail: 'support@xerxia.com',
  contactPhone: '+63 900 000 0000',
  address: 'Makati City, Metro Manila, Philippines',
  currency: 'PHP',
  timezone: 'Asia/Manila',
  maintenanceMode: false
})

// Payment Settings
const paymentMethods = ref([
  { id: 1, name: 'Credit/Debit Card', description: 'Accept major credit and debit cards', enabled: true, color: 'bg-blue-500' },
  { id: 2, name: 'GCash', description: 'Philippine mobile wallet', enabled: true, color: 'bg-green-500' },
  { id: 3, name: 'PayMaya', description: 'Philippine digital payment', enabled: true, color: 'bg-purple-500' },
  { id: 4, name: 'Bank Transfer', description: 'Direct bank transfer', enabled: true, color: 'bg-indigo-500' },
  { id: 5, name: 'Cash on Delivery', description: 'Pay when you receive', enabled: true, color: 'bg-yellow-500' }
])

const paymentSettings = ref({
  taxRate: 12,
  taxIncluded: false
})

// Shipping Settings
const shippingZones = ref([
  { id: 1, name: 'Metro Manila', areas: 'All NCR cities', rate: 150, estimatedDays: '1-2 days' },
  { id: 2, name: 'Luzon', areas: 'Outside Metro Manila', rate: 250, estimatedDays: '2-4 days' },
  { id: 3, name: 'Visayas', areas: 'Cebu, Iloilo, Bacolod, etc.', rate: 350, estimatedDays: '3-5 days' },
  { id: 4, name: 'Mindanao', areas: 'Davao, Cagayan de Oro, etc.', rate: 400, estimatedDays: '4-6 days' }
])

const shippingSettings = ref({
  freeShippingEnabled: true,
  freeShippingMin: 5000
})

// Email Settings
const emailSettings = ref({
  fromEmail: 'noreply@xerxia.com',
  fromName: 'Xerxia Wine Marketplace'
})

const emailTemplates = ref([
  { id: 1, name: 'Order Confirmation', description: 'Sent when order is placed' },
  { id: 2, name: 'Order Shipped', description: 'Sent when order is shipped' },
  { id: 3, name: 'Order Delivered', description: 'Sent when order is delivered' },
  { id: 4, name: 'Welcome Email', description: 'Sent to new customers' },
  { id: 5, name: 'Password Reset', description: 'Sent when password is reset' }
])

// Notification Settings
const adminNotifications = ref([
  { id: 1, name: 'New Order', description: 'Notify when new order is placed', enabled: true },
  { id: 2, name: 'Low Stock Alert', description: 'Notify when product stock is low', enabled: true },
  { id: 3, name: 'New Vendor Registration', description: 'Notify when new vendor registers', enabled: true },
  { id: 4, name: 'Order Cancellation', description: 'Notify when order is cancelled', enabled: true }
])

const customerNotifications = ref([
  { id: 1, name: 'Order Confirmation', description: 'Notify customer when order is confirmed', enabled: true },
  { id: 2, name: 'Order Shipped', description: 'Notify customer when order is shipped', enabled: true },
  { id: 3, name: 'Order Delivered', description: 'Notify customer when order is delivered', enabled: true },
  { id: 4, name: 'Promotional Emails', description: 'Send promotional offers to customers', enabled: false }
])

const saveSettings = () => {
  console.log('Settings saved!')
  // In real app, make API call to save settings
}

const editZone = (zone) => {
  console.log('Edit zone:', zone)
  // In real app, open edit modal
}

const formatNumber = (num) => {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
}
</script>
