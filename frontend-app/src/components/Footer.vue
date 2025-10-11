<template>
  <footer class="bg-gradient-to-br from-primary-950 via-primary-900 to-burgundy-950 text-neutral-100 mt-16 border-t-4 border-gold-500">
    <div class="container mx-auto px-4 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- About -->
        <div>
          <div class="flex items-center gap-2 mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-gold-400 to-gold-600 rounded-lg flex items-center justify-center shadow-lg">
              <span class="text-2xl font-bold font-['Playfair_Display'] text-primary-900">X</span>
            </div>
            <h3 class="text-white text-xl font-bold font-['Playfair_Display']">Xerxia</h3>
          </div>
          <p class="text-sm text-neutral-200 leading-relaxed">
            Your premium marketplace for curated quality products. Elegance in every purchase, excellence in every experience.
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-gold-300 text-lg font-semibold mb-4 font-['Playfair_Display']">Quick Links</h3>
          <ul class="space-y-2 text-sm">
            <li><router-link to="/" class="hover:text-gold-300 transition-colors duration-300">Home</router-link></li>
            <li><router-link to="/products" class="hover:text-gold-300 transition-colors duration-300">Products</router-link></li>
            <li><a href="#" class="hover:text-gold-300 transition-colors duration-300">About Us</a></li>
            <li><a href="#" class="hover:text-gold-300 transition-colors duration-300">Contact</a></li>
          </ul>
        </div>

        <!-- Customer Service -->
        <div>
          <h3 class="text-gold-300 text-lg font-semibold mb-4 font-['Playfair_Display']">Customer Service</h3>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:text-gold-300 transition-colors duration-300">Help Center</a></li>
            <li><a href="#" class="hover:text-gold-300 transition-colors duration-300">Shipping Info</a></li>
            <li><a href="#" class="hover:text-gold-300 transition-colors duration-300">Returns</a></li>
            <li><a href="#" class="hover:text-gold-300 transition-colors duration-300">FAQs</a></li>
          </ul>
        </div>

        <!-- For Vendors -->
        <div>
          <h3 class="text-gold-300 text-lg font-semibold mb-4 font-['Playfair_Display']">For Vendors</h3>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:text-gold-300 transition-colors duration-300">Sell on Xerxia</a></li>
            <li><a href="#" class="hover:text-gold-300 transition-colors duration-300">Vendor Guide</a></li>
            <li><a href="#" class="hover:text-gold-300 transition-colors duration-300">Success Stories</a></li>
            <li><router-link to="/login" class="hover:text-gold-300 transition-colors duration-300">Vendor Login</router-link></li>
          </ul>
        </div>
      </div>

      <div class="border-t border-primary-800 mt-8 pt-8 text-sm text-center">
        <p class="text-neutral-200">&copy; {{ new Date().getFullYear() }} <span class="font-semibold text-gold-300">Xerxia</span>. All rights reserved. <span class="text-neutral-300">Elegance in every purchase.</span></p>
      </div>
    </div>
  </footer>
</template>

<script setup>
</script>
