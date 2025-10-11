<template>
  <footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="container mx-auto px-4 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- About -->
        <div>
          <h3 class="text-white text-lg font-semibold mb-4">About IndoMarket</h3>
          <p class="text-sm">
            Your trusted multi-vendor marketplace for quality products from verified sellers across Indonesia.
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-white text-lg font-semibold mb-4">Quick Links</h3>
          <ul class="space-y-2 text-sm">
            <li><router-link to="/" class="hover:text-white">Home</router-link></li>
            <li><router-link to="/products" class="hover:text-white">Products</router-link></li>
            <li><a href="#" class="hover:text-white">About Us</a></li>
            <li><a href="#" class="hover:text-white">Contact</a></li>
          </ul>
        </div>

        <!-- Customer Service -->
        <div>
          <h3 class="text-white text-lg font-semibold mb-4">Customer Service</h3>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:text-white">Help Center</a></li>
            <li><a href="#" class="hover:text-white">Shipping Info</a></li>
            <li><a href="#" class="hover:text-white">Returns</a></li>
            <li><a href="#" class="hover:text-white">FAQs</a></li>
          </ul>
        </div>

        <!-- For Vendors -->
        <div>
          <h3 class="text-white text-lg font-semibold mb-4">For Vendors</h3>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:text-white">Sell on IndoMarket</a></li>
            <li><a href="#" class="hover:text-white">Vendor Guide</a></li>
            <li><a href="#" class="hover:text-white">Success Stories</a></li>
            <li><router-link to="/login" class="hover:text-white">Vendor Login</router-link></li>
          </ul>
        </div>
      </div>

      <div class="border-t border-gray-800 mt-8 pt-8 text-sm text-center">
        <p>&copy; {{ new Date().getFullYear() }} IndoMarket. All rights reserved.</p>
      </div>
    </div>
  </footer>
</template>

<script setup>
</script>
