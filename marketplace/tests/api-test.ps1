# API Testing Script for E-Commerce Platform
# This script tests all major API endpoints

$BaseUrl = "http://127.0.0.1:8000/api"
$TestResults = @()

function Test-Endpoint {
    param(
        [string]$Name,
        [string]$Method,
        [string]$Url,
        [hashtable]$Headers = @{},
        [object]$Body = $null
    )
    
    Write-Host "`n=== Testing: $Name ===" -ForegroundColor Cyan
    
    try {
        $params = @{
            Uri = $Url
            Method = $Method
            Headers = $Headers
            UseBasicParsing = $true
        }
        
        if ($Body) {
            $params.Body = ($Body | ConvertTo-Json -Depth 10)
            $params.ContentType = "application/json"
        }
        
        $response = Invoke-WebRequest @params
        
        Write-Host "SUCCESS - Status: $($response.StatusCode)" -ForegroundColor Green
        
        $script:TestResults += [PSCustomObject]@{
            Test = $Name
            Status = "PASS"
            StatusCode = $response.StatusCode
            Message = "Success"
        }
        
        return $response
    }
    catch {
        $statusCode = $_.Exception.Response.StatusCode.value__
        Write-Host "FAILED - Status: $statusCode" -ForegroundColor Red
        Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Red
        
        $script:TestResults += [PSCustomObject]@{
            Test = $Name
            Status = "FAIL"
            StatusCode = $statusCode
            Message = $_.Exception.Message
        }
        
        return $null
    }
}

Write-Host "`n╔══════════════════════════════════════════════════════════════╗" -ForegroundColor Yellow
Write-Host "║         E-Commerce Platform API Testing Suite              ║" -ForegroundColor Yellow
Write-Host "╚══════════════════════════════════════════════════════════════╝" -ForegroundColor Yellow

# Test 1: User Registration
Write-Host "`n--- Phase 1: Authentication Tests ---" -ForegroundColor Magenta
$registerData = @{
    name = "Test Customer"
    email = "testuser$(Get-Random)@test.com"
    password = "password123"
    password_confirmation = "password123"
    phone = "+1234567890"
}
$registerResponse = Test-Endpoint -Name "User Registration" -Method POST -Url "$BaseUrl/auth/register" -Body $registerData

# Test 2: User Login (use existing customer)
$loginData = @{
    email = "john.doe@customer.com"
    password = "password"
}
$loginResponse = Test-Endpoint -Name "User Login" -Method POST -Url "$BaseUrl/auth/login" -Body $loginData

$token = $null
if ($loginResponse) {
    $loginJson = $loginResponse.Content | ConvertFrom-Json
    $token = $loginJson.token
    Write-Host "Token received: $($token.Substring(0, 20))..." -ForegroundColor Gray
}

$authHeaders = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
}

# Test 3: Get User Profile
Test-Endpoint -Name "Get User Profile" -Method GET -Url "$BaseUrl/auth/profile" -Headers $authHeaders

# Test 4: Get All Categories
Write-Host "`n--- Phase 2: Category Tests ---" -ForegroundColor Magenta
Test-Endpoint -Name "Get All Categories" -Method GET -Url "$BaseUrl/categories"

# Test 5: Get Single Category
Test-Endpoint -Name "Get Single Category" -Method GET -Url "$BaseUrl/categories/1"

# Test 6: Get Products by Category
Test-Endpoint -Name "Get Category Products" -Method GET -Url "$BaseUrl/categories/1/products"

# Test 7: Get All Products
Write-Host "`n--- Phase 3: Product Tests ---" -ForegroundColor Magenta
Test-Endpoint -Name "Get All Products" -Method GET -Url "$BaseUrl/products"

# Test 8: Get Products with Filters
$filteredUrl = $BaseUrl + '/products?status=active&per_page=5'
Test-Endpoint -Name "Get Products (Filtered)" -Method GET -Url $filteredUrl

# Test 9: Get Single Product
Test-Endpoint -Name "Get Single Product" -Method GET -Url "$BaseUrl/products/1"

# Test 10: Get Related Products
Test-Endpoint -Name "Get Related Products" -Method GET -Url "$BaseUrl/products/1/related"

# Test 11: Get All Vendors
Write-Host "`n--- Phase 4: Vendor Tests ---" -ForegroundColor Magenta
Test-Endpoint -Name "Get All Vendors" -Method GET -Url "$BaseUrl/vendors"

# Test 12: Get Single Vendor
Test-Endpoint -Name "Get Single Vendor" -Method GET -Url "$BaseUrl/vendors/1"

# Test 13: Get Vendor Products
Test-Endpoint -Name "Get Vendor Products" -Method GET -Url "$BaseUrl/vendors/1/products"

# Test 14: Add to Cart
Write-Host "`n--- Phase 5: Cart Tests ---" -ForegroundColor Magenta
$cartData = @{
    product_id = 1
    quantity = 2
}
Test-Endpoint -Name "Add Product to Cart" -Method POST -Url "$BaseUrl/cart/items" -Headers $authHeaders -Body $cartData

# Test 15: Get Cart
Test-Endpoint -Name "Get Cart" -Method GET -Url "$BaseUrl/cart" -Headers $authHeaders

# Test 16: Get Cart Summary
Test-Endpoint -Name "Get Cart Summary" -Method GET -Url "$BaseUrl/cart/summary" -Headers $authHeaders

# Test 17: Update Cart Item
$updateCartData = @{
    quantity = 3
}
Test-Endpoint -Name "Update Cart Item" -Method PUT -Url "$BaseUrl/cart/items/1" -Headers $authHeaders -Body $updateCartData

# Test 18: Get Orders
Write-Host "`n--- Phase 6: Order Tests ---" -ForegroundColor Magenta
Test-Endpoint -Name "Get User Orders" -Method GET -Url "$BaseUrl/orders" -Headers $authHeaders

# Admin Login for Admin Tests
Write-Host "`n--- Phase 7: Admin Tests ---" -ForegroundColor Magenta
$adminLoginData = @{
    email = "admin@marketplace.com"
    password = "password"
}
$adminLoginResponse = Test-Endpoint -Name "Admin Login" -Method POST -Url "$BaseUrl/auth/login" -Body $adminLoginData

$adminToken = $null
if ($adminLoginResponse) {
    $adminLoginJson = $adminLoginResponse.Content | ConvertFrom-Json
    $adminToken = $adminLoginJson.token
}

$adminHeaders = @{
    "Authorization" = "Bearer $adminToken"
    "Accept" = "application/json"
}

# Test 19: Get All Vendors (Admin)
Test-Endpoint -Name "Admin - Get All Vendors" -Method GET -Url "$BaseUrl/admin/vendors" -Headers $adminHeaders

# Test 20: Get All Orders (Admin)
Test-Endpoint -Name "Admin - Get All Orders" -Method GET -Url "$BaseUrl/admin/orders" -Headers $adminHeaders

# Vendor Login for Vendor Tests
Write-Host "`n--- Phase 8: Vendor Tests ---" -ForegroundColor Magenta
$vendorLoginData = @{
    email = "techstore@vendor.com"
    password = "password"
}
$vendorLoginResponse = Test-Endpoint -Name "Vendor Login" -Method POST -Url "$BaseUrl/auth/login" -Body $vendorLoginData

$vendorToken = $null
if ($vendorLoginResponse) {
    $vendorLoginJson = $vendorLoginResponse.Content | ConvertFrom-Json
    $vendorToken = $vendorLoginJson.token
}

$vendorHeaders = @{
    "Authorization" = "Bearer $vendorToken"
    "Accept" = "application/json"
}

# Test 21: Get Vendor Dashboard
Test-Endpoint -Name "Vendor Dashboard" -Method GET -Url "$BaseUrl/vendor/dashboard" -Headers $vendorHeaders

# Test 22: Get Vendor Products
Test-Endpoint -Name "Vendor - Get Products" -Method GET -Url "$BaseUrl/vendor/products" -Headers $vendorHeaders

# Test 23: Get Vendor Orders
Test-Endpoint -Name "Vendor - Get Orders" -Method GET -Url "$BaseUrl/vendor/orders" -Headers $vendorHeaders

# Summary
Write-Host "`n`n╔══════════════════════════════════════════════════════════════╗" -ForegroundColor Yellow
Write-Host "║                    Test Summary                             ║" -ForegroundColor Yellow
Write-Host "╚══════════════════════════════════════════════════════════════╝" -ForegroundColor Yellow

$passCount = ($TestResults | Where-Object { $_.Status -eq "PASS" }).Count
$failCount = ($TestResults | Where-Object { $_.Status -eq "FAIL" }).Count
$totalCount = $TestResults.Count

Write-Host "`nTotal Tests: $totalCount" -ForegroundColor White
Write-Host "Passed: $passCount" -ForegroundColor Green
Write-Host "Failed: $failCount" -ForegroundColor Red
Write-Host "Success Rate: $([math]::Round(($passCount / $totalCount) * 100, 2))%" -ForegroundColor Cyan

Write-Host "`n--- Detailed Results ---" -ForegroundColor White
$TestResults | Format-Table -AutoSize

if ($failCount -gt 0) {
    Write-Host "`nFailed Tests:" -ForegroundColor Red
    $TestResults | Where-Object { $_.Status -eq "FAIL" } | Format-Table -AutoSize
}

Write-Host "`nAPI Testing Complete!" -ForegroundColor Green
