# 💵 Cash on Delivery (COD) Feature Guide

## ✨ New Feature Added

Customers can now pay with **Cash on Delivery (COD)** if they're located close to the shop!

---

## 🎯 How It Works

### Automatic Location Detection:

The system automatically checks if COD is available by:

1. **Getting customer's location** from their address
2. **Getting shop locations** from products in cart
3. **Comparing locations** (city/area matching)
4. **Enabling COD** if customer is in same city as any shop

---

## 📍 Location Matching Logic

### Supported Cities:

The system recognizes major Indonesian cities:
- Jakarta
- Bandung
- Surabaya
- Medan
- Semarang
- Makassar
- Palembang
- Tangerang
- Depok
- Bekasi
- Bogor
- Yogyakarta
- Malang
- Solo
- Denpasar

### Matching Rules:

✅ **Exact Match**: "Jakarta" = "Jakarta"
✅ **Partial Match**: "Jakarta Selatan" contains "Jakarta"
✅ **Case Insensitive**: "JAKARTA" = "jakarta"

---

## 🛒 Customer Experience

### Scenario 1: COD Available ✅

**Customer Address**: "Jl. Sudirman No. 123, Jakarta Pusat"
**Shop Location**: "Jakarta"

**What Customer Sees**:
```
┌─────────────────────────────────────────────┐
│ ✅ Cash on Delivery Available!              │
│ You're close to the shop. Pay when you     │
│ receive your order.                         │
├─────────────────────────────────────────────┤
│ ☑️ Cash on Delivery (COD) [CHECKED]        │
│    Pay with cash when your order arrives    │
├─────────────────────────────────────────────┤
│ ○ Debit Card                                │
│ ○ Credit Card                               │
│ ○ Digital Wallet                            │
└─────────────────────────────────────────────┘
```

---

### Scenario 2: COD Not Available ❌

**Customer Address**: "Jl. Raya No. 456, Bandung"
**Shop Location**: "Jakarta"

**What Customer Sees**:
```
┌─────────────────────────────────────────────┐
│ ☑️ Debit Card [CHECKED]                     │
│ ○ Credit Card                               │
│ ○ Digital Wallet                            │
├─────────────────────────────────────────────┤
│ ℹ️ Cash on Delivery not available          │
│ COD is only available if you're in the     │
│ same city as the shop.                      │
│ Shop locations: [Jakarta]                   │
└─────────────────────────────────────────────┘
```

---

## 🔄 Complete Workflow

### For Customers:

```
1. Add products to cart
   ↓
2. Go to checkout
   ↓
3. System checks location automatically
   ↓
4. If same city as shop:
   ✅ COD option appears (pre-selected)
   ↓
5. If different city:
   ❌ COD not shown
   ℹ️ Info message displayed
   ↓
6. Customer selects payment method
   ↓
7. Place order
   ↓
8. If COD selected:
   💵 Pay cash when order arrives
```

---

### For Sellers:

```
1. Customer places COD order
   ↓
2. Seller sees order with payment_method: "cod"
   ↓
3. Seller prepares order
   ↓
4. Seller marks as "Processing"
   ↓
5. Seller marks as "Shipped"
   ↓
6. Seller delivers order
   ↓
7. Seller collects cash payment
   ↓
8. Seller marks as "Delivered"
```

---

## 💡 How to Enable COD

### For Customers:

**Step 1: Update Your Address**
1. Go to Profile
2. Add your complete address with city name
3. Example: "Jl. Sudirman No. 123, **Jakarta** Pusat"

**Step 2: Shop from Local Sellers**
1. Check shop location on product page
2. Choose products from shops in your city

**Step 3: Checkout**
1. Go to cart
2. Click "Proceed to Checkout"
3. If COD available, it will appear automatically!

---

### For Sellers:

**Step 1: Set Shop Location**
1. Go to Seller Dashboard
2. Click "Shop Settings"
3. Enter your city in "Location" field
4. Example: "Jakarta", "Bandung", "Surabaya"
5. Save settings

**Step 2: Accept COD Orders**
1. Orders with COD will show payment_method: "cod"
2. Prepare order as usual
3. Collect cash payment on delivery
4. Mark order as delivered

---

## 🎨 Visual Examples

### Checkout Page with COD:

```
┌──────────────────────────────────────────────────────┐
│                     CHECKOUT                          │
├──────────────────────────────────────────────────────┤
│ Shipping Information                                  │
│ ┌──────────────────────────────────────────────────┐ │
│ │ Phone: 0812345678                                │ │
│ │ Address: Jl. Sudirman 123, Jakarta Pusat         │ │
│ └──────────────────────────────────────────────────┘ │
│                                                       │
│ Payment Method                                        │
│ ┌──────────────────────────────────────────────────┐ │
│ │ ✅ Cash on Delivery Available!                   │ │
│ │ You're close to the shop.                        │ │
│ └──────────────────────────────────────────────────┘ │
│                                                       │
│ ☑️ 💵 Cash on Delivery (COD)                        │
│    Pay with cash when your order arrives             │
│ ─────────────────────────────────────────────────── │
│ ○ 💳 Debit Card                                      │
│ ○ 💳 Credit Card                                     │
│ ○ 💰 Digital Wallet                                  │
│                                                       │
│ [Place Order]                                         │
└──────────────────────────────────────────────────────┘
```

---

## 🔐 Security & Validation

### Backend Validation:

✅ **COD Availability Check**:
- Validates location match before allowing COD
- Returns error if COD selected but not available

✅ **Payment Method Validation**:
- Accepts: debit_card, credit_card, digital_wallet, cod
- Rejects invalid payment methods

✅ **Location Extraction**:
- Extracts city from full address
- Handles various address formats
- Case-insensitive matching

---

## 📊 Location Matching Examples

### Example 1: Exact Match ✅
```
Customer: "Jakarta"
Shop: "Jakarta"
Result: COD Available
```

### Example 2: Partial Match ✅
```
Customer: "Jl. Sudirman, Jakarta Selatan"
Shop: "Jakarta"
Result: COD Available (contains "Jakarta")
```

### Example 3: Different Cities ❌
```
Customer: "Bandung"
Shop: "Jakarta"
Result: COD Not Available
```

### Example 4: Multiple Shops ✅
```
Customer: "Jakarta"
Shop A: "Bandung" (not match)
Shop B: "Jakarta" (match!)
Result: COD Available (at least one shop matches)
```

### Example 5: No Address ❌
```
Customer: (no address set)
Shop: "Jakarta"
Result: COD Not Available
```

---

## 🎯 Use Cases

### Use Case 1: Local Shopping
```
Customer in Jakarta wants to buy from Jakarta shop
→ COD available
→ Customer selects COD
→ Order placed
→ Seller delivers and collects cash
```

### Use Case 2: Cross-City Shopping
```
Customer in Bandung wants to buy from Jakarta shop
→ COD not available
→ Customer uses credit card
→ Order shipped via courier
→ Payment already processed
```

### Use Case 3: Multi-Vendor Cart
```
Customer in Jakarta has cart with:
- Product A from Jakarta shop (COD available)
- Product B from Bandung shop (COD not available)
→ COD available (at least one shop is local)
→ Customer can choose COD
→ Both orders processed together
```

---

## 💻 Technical Implementation

### Controller Method:

```php
// Check if COD is available
private function checkCodAvailability($cartItems)
{
    $customerAddress = strtolower(auth()->user()->address ?? '');
    
    if (empty($customerAddress)) {
        return false;
    }
    
    $customerLocation = $this->extractLocation($customerAddress);
    
    foreach ($cartItems as $item) {
        $shop = $item->product->shop;
        
        if (!$shop || !$shop->location) {
            continue;
        }
        
        $shopLocation = $this->extractLocation(strtolower($shop->location));
        
        if ($this->locationsMatch($customerLocation, $shopLocation)) {
            return true;
        }
    }
    
    return false;
}
```

### View Logic:

```blade
@if($codAvailable)
    <!-- Show COD option -->
    <div class="alert alert-success">
        Cash on Delivery Available!
    </div>
@else
    <!-- Show info message -->
    <div class="alert alert-info">
        COD not available for your location
    </div>
@endif
```

---

## 📝 Database Changes

### Order Table:

**payment_method** field now accepts:
- `debit_card`
- `credit_card`
- `digital_wallet`
- **`cod`** ← NEW!

### No Migration Needed:

The payment_method field already exists and can store "cod" value.

---

## 🎓 Tips for Best Results

### For Customers:

1. **Set Complete Address**
   - Include city name clearly
   - Example: "Jl. Raya 123, **Jakarta** Selatan"

2. **Shop Locally**
   - Check shop location before adding to cart
   - Local shops = COD available

3. **Update Profile**
   - Keep address up to date
   - Ensure city name is correct

---

### For Sellers:

1. **Set Clear Location**
   - Use city name only: "Jakarta", "Bandung"
   - Don't use full address in location field

2. **Accept COD Orders**
   - Be prepared to collect cash on delivery
   - Bring change for customers

3. **Verify Location**
   - Confirm customer location before delivery
   - Contact customer if address unclear

---

## 🔍 Troubleshooting

### COD Not Showing?

**Check:**
1. ✅ Customer has address set in profile
2. ✅ Address includes city name
3. ✅ Shop has location set
4. ✅ Customer and shop in same city

**Common Issues:**
- Address missing city name
- Shop location not set
- Typo in city name
- Different cities

---

### COD Selected But Error?

**Possible Causes:**
1. Location changed after checkout page loaded
2. Shop location removed
3. Backend validation failed

**Solution:**
- Refresh checkout page
- Verify address and shop location
- Try again

---

## 📞 Quick Reference

### Payment Methods:

| Method | Code | Description |
|--------|------|-------------|
| Debit Card | `debit_card` | Pay with debit card |
| Credit Card | `credit_card` | Pay with credit card |
| Digital Wallet | `digital_wallet` | Pay with e-wallet |
| **Cash on Delivery** | **`cod`** | **Pay cash on delivery** |

### Location Matching:

| Customer | Shop | COD Available? |
|----------|------|----------------|
| Jakarta | Jakarta | ✅ Yes |
| Jakarta Selatan | Jakarta | ✅ Yes |
| Bandung | Jakarta | ❌ No |
| (no address) | Jakarta | ❌ No |

---

## ✅ Summary

**COD Feature Includes:**
- ✅ Automatic location detection
- ✅ Smart city matching
- ✅ Visual indicators (success/info alerts)
- ✅ Backend validation
- ✅ Multi-vendor support
- ✅ User-friendly messages
- ✅ Security checks

**Benefits:**
- 💵 Convenient for local customers
- 🏪 Encourages local shopping
- 🚀 Increases conversion rates
- 🤝 Builds trust with customers
- 📦 Supports multi-vendor marketplace

---

## 🎉 Start Using COD

**For Customers:**
1. Update your address with city name
2. Shop from local sellers
3. Checkout and select COD!

**For Sellers:**
1. Set your shop location
2. Accept COD orders
3. Deliver and collect cash!

**Happy Shopping & Selling!** 💵🛍️
