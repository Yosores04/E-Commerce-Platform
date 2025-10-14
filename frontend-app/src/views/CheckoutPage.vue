<template>
  <DefaultLayout>
    <div class="bg-whitesmoke min-h-screen">
      <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
          <p class="text-gray-600 mt-1">Complete your purchase in a few simple steps</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Checkout Steps -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Steps Progress -->
            <div class="bg-white rounded-lg shadow-md p-6">
              <div class="flex items-center justify-between">
                <div 
                  v-for="(step, index) in steps"
                  :key="step.id"
                  class="flex items-center"
                  :class="{ 'flex-1': index < steps.length - 1 }"
                >
                  <div class="flex flex-col items-center">
                    <div 
                      :class="[
                        'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm',
                        currentStep >= step.id 
                          ? 'bg-wine text-white' 
                          : 'bg-gray-200 text-gray-600'
                      ]"
                    >
                      {{ step.id }}
                    </div>
                    <span 
                      :class="[
                        'text-xs mt-2 hidden sm:block',
                        currentStep >= step.id ? 'text-wine font-medium' : 'text-gray-600'
                      ]"
                    >
                      {{ step.label }}
                    </span>
                  </div>
                  <div 
                    v-if="index < steps.length - 1"
                    :class="[
                      'flex-1 h-1 mx-2',
                      currentStep > step.id ? 'bg-wine' : 'bg-gray-200'
                    ]"
                  ></div>
                </div>
              </div>
            </div>

            <!-- Step 1: Shipping Address -->
            <div v-show="currentStep === 1" class="bg-white rounded-lg shadow-md p-6">
              <h2 class="text-xl font-bold text-gray-900 mb-6">Shipping Address</h2>
              
              <!-- Saved Addresses -->
              <div v-if="savedAddresses.length > 0" class="mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Select a saved address</h3>
                <div class="space-y-3">
                  <label
                    v-for="address in savedAddresses"
                    :key="address.id"
                    class="flex items-start space-x-3 p-4 border-2 rounded-lg cursor-pointer transition-all"
                    :class="selectedShippingAddress?.id === address.id 
                      ? 'border-wine bg-wine/5' 
                      : 'border-gray-200 hover:border-wine/50'"
                  >
                    <input
                      type="radio"
                      :value="address"
                      v-model="selectedShippingAddress"
                      class="mt-1 w-5 h-5 text-wine border-gray-300 focus:ring-wine"
                    />
                    <div class="flex-1">
                      <div class="font-medium text-gray-900">{{ address.full_name }}</div>
                      <div class="text-sm text-gray-600 mt-1">
                        {{ address.address_line1 }}
                        <span v-if="address.address_line2">, {{ address.address_line2 }}</span>
                      </div>
                      <div class="text-sm text-gray-600">
                        {{ address.city }}, {{ address.state }} {{ address.postal_code }}
                      </div>
                      <div class="text-sm text-gray-600">{{ address.phone }}</div>
                      <span v-if="address.is_default" class="inline-block mt-2 px-2 py-1 bg-wine text-white text-xs rounded">
                        Default
                      </span>
                    </div>
                  </label>
                </div>
              </div>

              <!-- Add New Address Form -->
              <div>
                <button
                  v-if="!showAddressForm"
                  @click="showAddressForm = true"
                  class="flex items-center space-x-2 text-wine hover:text-wine/80 font-medium"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  <span>Add New Address</span>
                </button>

                <div v-else class="space-y-4">
                  <h3 class="text-sm font-semibold text-gray-700 mb-3">Add New Address</h3>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                      <input
                        v-model="newAddress.full_name"
                        type="text"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wine focus:border-transparent"
                        placeholder="John Doe"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                      <input
                        v-model="newAddress.phone"
                        type="tel"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wine focus:border-transparent"
                        placeholder="+63 912 345 6789"
                      />
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1 *</label>
                    <input
                      v-model="newAddress.address_line1"
                      type="text"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wine focus:border-transparent"
                      placeholder="House/Unit/Floor, Building Name"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                    <input
                      v-model="newAddress.address_line2"
                      type="text"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wine focus:border-transparent"
                      placeholder="Street Name, Barangay"
                    />
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                      <input
                        v-model="newAddress.city"
                        type="text"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wine focus:border-transparent"
                        placeholder="Manila"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Province *</label>
                      <input
                        v-model="newAddress.state"
                        type="text"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wine focus:border-transparent"
                        placeholder="Metro Manila"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code *</label>
                      <input
                        v-model="newAddress.postal_code"
                        type="text"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-wine focus:border-transparent"
                        placeholder="1000"
                      />
                    </div>
                  </div>

                  <div class="flex items-center space-x-2">
                    <input
                      v-model="newAddress.is_default"
                      type="checkbox"
                      class="w-4 h-4 text-wine border-gray-300 rounded focus:ring-wine"
                    />
                    <label class="text-sm text-gray-700">Set as default address</label>
                  </div>

                  <div class="flex space-x-3">
                    <button
                      @click="saveNewAddress"
                      class="px-6 py-2 bg-wine text-white rounded-lg hover:bg-wine/90 transition-colors"
                    >
                      Save Address
                    </button>
                    <button
                      @click="cancelAddressForm"
                      class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                      Cancel
                    </button>
                  </div>
                </div>
              </div>

              <!-- Navigation Buttons -->
              <div class="mt-6 pt-6 border-t border-gray-200 flex justify-end">
                <button
                  @click="nextStep"
                  :disabled="!selectedShippingAddress"
                  class="px-8 py-3 bg-wine text-white rounded-lg font-semibold hover:bg-wine/90 disabled:bg-gray-300 disabled:cursor-not-allowed transition-all"
                >
                  Continue to Shipping Method
                </button>
              </div>
            </div>

            <!-- Step 2: Shipping Method -->
            <div v-show="currentStep === 2" class="bg-white rounded-lg shadow-md p-6">
              <h2 class="text-xl font-bold text-gray-900 mb-6">Shipping Method</h2>
              
              <div class="space-y-3">
                <label
                  v-for="method in shippingMethods"
                  :key="method.id"
                  class="flex items-center justify-between p-4 border-2 rounded-lg cursor-pointer transition-all"
                  :class="selectedShippingMethod?.id === method.id 
                    ? 'border-wine bg-wine/5' 
                    : 'border-gray-200 hover:border-wine/50'"
                >
                  <div class="flex items-center space-x-3">
                    <input
                      type="radio"
                      :value="method"
                      v-model="selectedShippingMethod"
                      class="w-5 h-5 text-wine border-gray-300 focus:ring-wine"
                    />
                    <div>
                      <div class="font-medium text-gray-900">{{ method.name }}</div>
                      <div class="text-sm text-gray-600">{{ method.description }}</div>
                      <div class="text-sm text-gray-600">Estimated delivery: {{ method.estimated_days }} days</div>
                    </div>
                  </div>
                  <div class="text-lg font-bold text-wine">
                    {{ method.cost > 0 ? '₱' + formatPrice(method.cost) : 'FREE' }}
                  </div>
                </label>
              </div>

              <!-- Navigation Buttons -->
              <div class="mt-6 pt-6 border-t border-gray-200 flex justify-between">
                <button
                  @click="previousStep"
                  class="px-6 py-3 border-2 border-wine text-wine rounded-lg font-semibold hover:bg-wine hover:text-white transition-all"
                >
                  Back
                </button>
                <button
                  @click="nextStep"
                  :disabled="!selectedShippingMethod"
                  class="px-8 py-3 bg-wine text-white rounded-lg font-semibold hover:bg-wine/90 disabled:bg-gray-300 disabled:cursor-not-allowed transition-all"
                >
                  Continue to Payment
                </button>
              </div>
            </div>

            <!-- Step 3: Payment Method -->
            <div v-show="currentStep === 3" class="bg-white rounded-lg shadow-md p-6">
              <h2 class="text-xl font-bold text-gray-900 mb-6">Payment Method</h2>
              
              <div class="space-y-3">
                <label
                  v-for="method in paymentMethods"
                  :key="method.id"
                  class="flex items-start space-x-3 p-4 border-2 rounded-lg cursor-pointer transition-all"
                  :class="selectedPaymentMethod?.id === method.id 
                    ? 'border-wine bg-wine/5' 
                    : 'border-gray-200 hover:border-wine/50'"
                >
                  <input
                    type="radio"
                    :value="method"
                    v-model="selectedPaymentMethod"
                    class="mt-1 w-5 h-5 text-wine border-gray-300 focus:ring-wine"
                  />
                  <div class="flex-1">
                    <div class="flex items-center space-x-2">
                      <div class="font-medium text-gray-900">{{ method.name }}</div>
                      <img v-if="method.icon" :src="method.icon" :alt="method.name" class="h-6" />
                    </div>
                    <div class="text-sm text-gray-600 mt-1">{{ method.description }}</div>
                  </div>
                </label>
              </div>

              <!-- Navigation Buttons -->
              <div class="mt-6 pt-6 border-t border-gray-200 flex justify-between">
                <button
                  @click="previousStep"
                  class="px-6 py-3 border-2 border-wine text-wine rounded-lg font-semibold hover:bg-wine hover:text-white transition-all"
                >
                  Back
                </button>
                <button
                  @click="nextStep"
                  :disabled="!selectedPaymentMethod"
                  class="px-8 py-3 bg-wine text-white rounded-lg font-semibold hover:bg-wine/90 disabled:bg-gray-300 disabled:cursor-not-allowed transition-all"
                >
                  Review Order
                </button>
              </div>
            </div>

            <!-- Step 4: Review Order -->
            <div v-show="currentStep === 4" class="bg-white rounded-lg shadow-md p-6">
              <h2 class="text-xl font-bold text-gray-900 mb-6">Review Your Order</h2>
              
              <!-- Shipping Address Review -->
              <div class="mb-6 pb-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-3">
                  <h3 class="font-semibold text-gray-900">Shipping Address</h3>
                  <button @click="currentStep = 1" class="text-wine hover:underline text-sm">Edit</button>
                </div>
                <div v-if="selectedShippingAddress" class="text-sm text-gray-600">
                  <div class="font-medium text-gray-900">{{ selectedShippingAddress.full_name }}</div>
                  <div>{{ selectedShippingAddress.address_line1 }}</div>
                  <div v-if="selectedShippingAddress.address_line2">{{ selectedShippingAddress.address_line2 }}</div>
                  <div>{{ selectedShippingAddress.city }}, {{ selectedShippingAddress.state }} {{ selectedShippingAddress.postal_code }}</div>
                  <div>{{ selectedShippingAddress.phone }}</div>
                </div>
              </div>

              <!-- Shipping Method Review -->
              <div class="mb-6 pb-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-3">
                  <h3 class="font-semibold text-gray-900">Shipping Method</h3>
                  <button @click="currentStep = 2" class="text-wine hover:underline text-sm">Edit</button>
                </div>
                <div v-if="selectedShippingMethod" class="flex justify-between text-sm">
                  <div>
                    <div class="font-medium text-gray-900">{{ selectedShippingMethod.name }}</div>
                    <div class="text-gray-600">Estimated delivery: {{ selectedShippingMethod.estimated_days }} days</div>
                  </div>
                  <div class="font-medium text-wine">
                    {{ selectedShippingMethod.cost > 0 ? '₱' + formatPrice(selectedShippingMethod.cost) : 'FREE' }}
                  </div>
                </div>
              </div>

              <!-- Payment Method Review -->
              <div class="mb-6 pb-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-3">
                  <h3 class="font-semibold text-gray-900">Payment Method</h3>
                  <button @click="currentStep = 3" class="text-wine hover:underline text-sm">Edit</button>
                </div>
                <div v-if="selectedPaymentMethod" class="text-sm">
                  <div class="font-medium text-gray-900">{{ selectedPaymentMethod.name }}</div>
                  <div class="text-gray-600">{{ selectedPaymentMethod.description }}</div>
                </div>
              </div>

              <!-- Terms & Conditions -->
              <div class="mb-6">
                <label class="flex items-start space-x-3">
                  <input
                    v-model="agreedToTerms"
                    type="checkbox"
                    class="mt-1 w-5 h-5 text-wine border-gray-300 rounded focus:ring-wine"
                  />
                  <span class="text-sm text-gray-700">
                    I agree to the <a href="#" class="text-wine hover:underline">Terms & Conditions</a> 
                    and <a href="#" class="text-wine hover:underline">Privacy Policy</a>
                  </span>
                </label>
              </div>

              <!-- Navigation Buttons -->
              <div class="flex justify-between">
                <button
                  @click="previousStep"
                  class="px-6 py-3 border-2 border-wine text-wine rounded-lg font-semibold hover:bg-wine hover:text-white transition-all"
                >
                  Back
                </button>
                <button
                  @click="placeOrder"
                  :disabled="!agreedToTerms || isPlacingOrder"
                  class="px-8 py-3 bg-wine text-white rounded-lg font-semibold hover:bg-wine/90 disabled:bg-gray-300 disabled:cursor-not-allowed transition-all flex items-center space-x-2"
                >
                  <span>{{ isPlacingOrder ? 'Placing Order...' : 'Place Order' }}</span>
                  <svg v-if="!isPlacingOrder" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Order Summary Sidebar -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
              <h2 class="text-lg font-bold text-gray-900 mb-4 pb-4 border-b border-gray-200">Order Summary</h2>
              
              <!-- Cart Items -->
              <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                <div v-for="item in cartStore.items" :key="item.id" class="flex space-x-3">
                  <div class="w-16 h-16 rounded bg-gray-100 flex-shrink-0">
                    <img 
                      :src="getProductImage(item.product)"
                      :alt="item.product?.name"
                      class="w-full h-full object-cover rounded"
                    />
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-900 truncate">{{ item.product?.name }}</div>
                    <div class="text-xs text-gray-600">Qty: {{ item.quantity }}</div>
                    <div class="text-sm font-medium text-wine">₱{{ formatPrice(item.price * item.quantity) }}</div>
                  </div>
                </div>
              </div>

              <div class="space-y-3 pt-4 border-t border-gray-200">
                <div class="flex justify-between text-sm text-gray-700">
                  <span>Subtotal</span>
                  <span class="font-medium">₱{{ formatPrice(cartStore.subtotal) }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-700">
                  <span>Shipping</span>
                  <span class="font-medium">
                    {{ selectedShippingMethod?.cost ? '₱' + formatPrice(selectedShippingMethod.cost) : 'TBD' }}
                  </span>
                </div>
                <div class="flex justify-between text-sm text-gray-700">
                  <span>Tax (12%)</span>
                  <span class="font-medium">₱{{ formatPrice(calculateTax()) }}</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex justify-between items-center">
                  <span class="font-bold text-gray-900">Total</span>
                  <span class="text-2xl font-bold text-wine">₱{{ formatPrice(calculateTotal()) }}</span>
                </div>
              </div>

              <!-- Security Badge -->
              <div class="mt-6 pt-6 border-t border-gray-200 flex items-center justify-center space-x-2 text-sm text-gray-600">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Secure Checkout</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import DefaultLayout from '../layouts/DefaultLayout.vue'
import { useCartStore } from '../stores/cart'
import { useAuthStore } from '../stores/auth'
import { orderService } from '../services/order'

const router = useRouter()
const cartStore = useCartStore()
const authStore = useAuthStore()

const currentStep = ref(1)
const isPlacingOrder = ref(false)
const agreedToTerms = ref(false)
const showAddressForm = ref(false)

const steps = [
  { id: 1, label: 'Shipping' },
  { id: 2, label: 'Delivery' },
  { id: 3, label: 'Payment' },
  { id: 4, label: 'Review' }
]

// Addresses
const savedAddresses = ref([
  {
    id: 1,
    full_name: 'Juan Dela Cruz',
    address_line1: 'Unit 123, Building A',
    address_line2: 'Makati Avenue, Poblacion',
    city: 'Makati',
    state: 'Metro Manila',
    postal_code: '1210',
    phone: '+63 912 345 6789',
    is_default: true
  }
])

const selectedShippingAddress = ref(savedAddresses.value[0])

const newAddress = ref({
  full_name: '',
  phone: '',
  address_line1: '',
  address_line2: '',
  city: '',
  state: '',
  postal_code: '',
  country: 'Philippines',
  is_default: false
})

// Shipping Methods
const shippingMethods = ref([
  {
    id: 1,
    name: 'Standard Shipping',
    description: 'Delivery within Metro Manila',
    estimated_days: '3-5',
    cost: 150
  },
  {
    id: 2,
    name: 'Express Shipping',
    description: 'Next-day delivery',
    estimated_days: '1-2',
    cost: 350
  },
  {
    id: 3,
    name: 'Free Shipping',
    description: 'Free shipping for orders over ₱2,500',
    estimated_days: '5-7',
    cost: 0
  }
])

const selectedShippingMethod = ref(null)

// Payment Methods
const paymentMethods = ref([
  {
    id: 1,
    name: 'Credit/Debit Card',
    description: 'Pay securely with your credit or debit card',
    value: 'credit_card'
  },
  {
    id: 2,
    name: 'GCash',
    description: 'Pay using GCash e-wallet',
    value: 'gcash'
  },
  {
    id: 3,
    name: 'PayMaya',
    description: 'Pay using PayMaya',
    value: 'paymaya'
  },
  {
    id: 4,
    name: 'Bank Transfer',
    description: 'Direct bank transfer',
    value: 'bank_transfer'
  },
  {
    id: 5,
    name: 'Cash on Delivery',
    description: 'Pay with cash upon delivery',
    value: 'cash_on_delivery'
  }
])

const selectedPaymentMethod = ref(null)

onMounted(async () => {
  await cartStore.fetchCart()
  
  if (cartStore.isEmpty) {
    router.push('/cart')
  }
})

const getProductImage = (product) => {
  if (!product) return 'https://via.placeholder.com/200x200/5B2333/F7F4F3?text=No+Image'
  
  if (product.images && product.images.length > 0) {
    const primary = product.images.find(img => img.is_primary)
    return primary ? primary.image_path : product.images[0].image_path
  }
  
  return 'https://via.placeholder.com/200x200/5B2333/F7F4F3?text=No+Image'
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-PH').format(price)
}

const calculateTax = () => {
  const subtotal = cartStore.subtotal || 0
  return subtotal * 0.12 // 12% VAT
}

const calculateTotal = () => {
  const subtotal = cartStore.subtotal || 0
  const shipping = selectedShippingMethod.value?.cost || 0
  const tax = calculateTax()
  return subtotal + shipping + tax
}

const nextStep = () => {
  if (currentStep.value < 4) {
    currentStep.value++
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const previousStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const saveNewAddress = () => {
  // Validate form
  if (!newAddress.value.full_name || !newAddress.value.phone || 
      !newAddress.value.address_line1 || !newAddress.value.city || 
      !newAddress.value.state || !newAddress.value.postal_code) {
    alert('Please fill in all required fields')
    return
  }

  // Add to saved addresses
  const addressToSave = {
    id: savedAddresses.value.length + 1,
    ...newAddress.value
  }
  
  savedAddresses.value.push(addressToSave)
  selectedShippingAddress.value = addressToSave
  
  // Reset form
  newAddress.value = {
    full_name: '',
    phone: '',
    address_line1: '',
    address_line2: '',
    city: '',
    state: '',
    postal_code: '',
    country: 'Philippines',
    is_default: false
  }
  
  showAddressForm.value = false
}

const cancelAddressForm = () => {
  showAddressForm.value = false
  newAddress.value = {
    full_name: '',
    phone: '',
    address_line1: '',
    address_line2: '',
    city: '',
    state: '',
    postal_code: '',
    country: 'Philippines',
    is_default: false
  }
}

const placeOrder = async () => {
  if (!agreedToTerms.value) {
    alert('Please agree to the Terms & Conditions')
    return
  }

  isPlacingOrder.value = true

  try {
    // Prepare order data
    const orderData = {
      shipping_address: {
        full_name: shippingInfo.value.fullName,
        phone: shippingInfo.value.phone,
        address_line1: shippingInfo.value.address,
        address_line2: shippingInfo.value.apartment,
        city: shippingInfo.value.city,
        state: shippingInfo.value.province,
        postal_code: shippingInfo.value.postalCode,
        country: 'Philippines'
      },
      billing_address: {
        full_name: shippingInfo.value.fullName,
        phone: shippingInfo.value.phone,
        address_line1: shippingInfo.value.address,
        address_line2: shippingInfo.value.apartment,
        city: shippingInfo.value.city,
        state: shippingInfo.value.province,
        postal_code: shippingInfo.value.postalCode,
        country: 'Philippines'
      },
      shipping_method: selectedShippingMethod.value.id,
      payment_method: selectedPaymentMethod.value.value,
      notes: ''
    }
    
    // Create order
    const response = await orderService.createOrder(orderData)
    
    if (response.success) {
      // Clear cart after successful order
      await cartStore.clearCart()
      
      // Redirect to order confirmation with order data
      router.push({
        name: 'OrderConfirmation',
        params: { id: response.data.id },
        query: { order_number: response.data.order_number }
      })
    }
  } catch (error) {
    console.error('Failed to place order:', error)
    alert(error.response?.data?.message || 'Failed to place order. Please try again.')
  } finally {
    isPlacingOrder.value = false
  }
}
</script>
