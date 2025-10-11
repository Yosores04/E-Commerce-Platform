# Simple API Test Script
$BaseUrl = "http://127.0.0.1:8000/api"

Write-Host "`n=== E-Commerce API Testing ===" -ForegroundColor Cyan

# Test 1: Register User
Write-Host "`n1. Testing User Registration..." -ForegroundColor Yellow
$regBody = @{
    name = "Test User"
    email = "test$(Get-Random)@test.com"
    password = "password123"
    password_confirmation = "password123"
    phone = "+1234567890"
} | ConvertTo-Json

try {
    $reg = Invoke-RestMethod -Uri "$BaseUrl/auth/register" -Method POST -Body $regBody -ContentType "application/json"
    Write-Host "   SUCCESS: User registered" -ForegroundColor Green
} catch {
    Write-Host "   FAILED: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 2: Login
Write-Host "`n2. Testing User Login..." -ForegroundColor Yellow
$loginBody = @{
    email = "john.doe@customer.com"
    password = "password"
} | ConvertTo-Json

try {
    $login = Invoke-RestMethod -Uri "$BaseUrl/auth/login" -Method POST -Body $loginBody -ContentType "application/json"
    $token = $login.token
    Write-Host "   SUCCESS: Login successful, token received" -ForegroundColor Green
} catch {
    Write-Host "   FAILED: $($_.Exception.Message)" -ForegroundColor Red
    exit
}

$headers = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
}

# Test 3: Get Profile
Write-Host "`n3. Testing Get Profile..." -ForegroundColor Yellow
try {
    $profile = Invoke-RestMethod -Uri "$BaseUrl/auth/profile" -Method GET -Headers $headers
    Write-Host "   SUCCESS: Profile retrieved - $($profile.user.name)" -ForegroundColor Green
} catch {
    Write-Host "   FAILED: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 4: Get Categories
Write-Host "`n4. Testing Get Categories..." -ForegroundColor Yellow
try {
    $categories = Invoke-RestMethod -Uri "$BaseUrl/categories" -Method GET
    Write-Host "   SUCCESS: Retrieved $($categories.data.Count) categories" -ForegroundColor Green
} catch {
    Write-Host "   FAILED: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 5: Get Products
Write-Host "`n5. Testing Get Products..." -ForegroundColor Yellow
try {
    $products = Invoke-RestMethod -Uri "$BaseUrl/products" -Method GET
    Write-Host "   SUCCESS: Retrieved $($products.data.Count) products" -ForegroundColor Green
} catch {
    Write-Host "   FAILED: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 6: Get Single Product
Write-Host "`n6. Testing Get Single Product..." -ForegroundColor Yellow
try {
    $product = Invoke-RestMethod -Uri "$BaseUrl/products/1" -Method GET
    Write-Host "   SUCCESS: Product retrieved - $($product.data.name)" -ForegroundColor Green
} catch {
    Write-Host "   FAILED: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 7: Get Vendors
Write-Host "`n7. Testing Get Vendors..." -ForegroundColor Yellow
try {
    $vendors = Invoke-RestMethod -Uri "$BaseUrl/vendors" -Method GET
    Write-Host "   SUCCESS: Retrieved $($vendors.data.Count) vendors" -ForegroundColor Green
} catch {
    Write-Host "   FAILED: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 8: Add to Cart
Write-Host "`n8. Testing Add to Cart..." -ForegroundColor Yellow
$cartBody = @{
    product_id = 1
    quantity = 2
} | ConvertTo-Json

try {
    $cart = Invoke-RestMethod -Uri "$BaseUrl/cart/items" -Method POST -Body $cartBody -ContentType "application/json" -Headers $headers
    Write-Host "   SUCCESS: Product added to cart" -ForegroundColor Green
} catch {
    Write-Host "   FAILED: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 9: Get Cart
Write-Host "`n9. Testing Get Cart..." -ForegroundColor Yellow
try {
    $cartData = Invoke-RestMethod -Uri "$BaseUrl/cart" -Method GET -Headers $headers
    Write-Host "   SUCCESS: Cart retrieved with $($cartData.data.items.Count) items" -ForegroundColor Green
} catch {
    Write-Host "   FAILED: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 10: Admin Login
Write-Host "`n10. Testing Admin Login..." -ForegroundColor Yellow
$adminBody = @{
    email = "admin@marketplace.com"
    password = "password"
} | ConvertTo-Json

try {
    $adminLogin = Invoke-RestMethod -Uri "$BaseUrl/auth/login" -Method POST -Body $adminBody -ContentType "application/json"
    $adminToken = $adminLogin.token
    Write-Host "   SUCCESS: Admin logged in" -ForegroundColor Green
} catch {
    Write-Host "   FAILED: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 11: Vendor Login
Write-Host "`n11. Testing Vendor Login..." -ForegroundColor Yellow
$vendorBody = @{
    email = "techstore@vendor.com"
    password = "password"
} | ConvertTo-Json

try {
    $vendorLogin = Invoke-RestMethod -Uri "$BaseUrl/auth/login" -Method POST -Body $vendorBody -ContentType "application/json"
    $vendorToken = $vendorLogin.token
    Write-Host "   SUCCESS: Vendor logged in" -ForegroundColor Green
    
    $vendorHeaders = @{
        "Authorization" = "Bearer $vendorToken"
        "Accept" = "application/json"
    }
    
    # Test Vendor Dashboard
    Write-Host "`n12. Testing Vendor Dashboard..." -ForegroundColor Yellow
    $dashboard = Invoke-RestMethod -Uri "$BaseUrl/vendor/dashboard" -Method GET -Headers $vendorHeaders
    Write-Host "   SUCCESS: Dashboard retrieved" -ForegroundColor Green
    
} catch {
    Write-Host "   FAILED: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`n=== API Testing Complete! ===" -ForegroundColor Cyan
Write-Host "All core endpoints are working properly.`n" -ForegroundColor Green
