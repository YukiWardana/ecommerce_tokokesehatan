# 📸 Panduan Upload Gambar Produk

## ✅ Setup Sudah Lengkap!

Sistem upload gambar produk sudah dikonfigurasi dengan benar. Berikut detailnya:

---

## 📁 Struktur Folder

```
public/
├── images/
│   ├── products/     ← Gambar produk disimpan di sini
│   └── shops/        ← Logo toko disimpan di sini
```

**Status:** ✅ Folder sudah dibuat

---

## 🔧 Konfigurasi Controller

### Seller Product Controller
**File:** `app/Http/Controllers/Web/SellerController.php`

#### Upload Gambar Saat Create Product (Line 130-170)

```php
public function storeProduct(Request $request)
{
    // Validasi termasuk gambar
    $validated = $request->validate([
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        // ... field lainnya
    ]);

    // Handle upload gambar
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        
        // Generate nama file unik: timestamp_uniqueid.extension
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        
        // Pindahkan ke public/images/products
        $image->move(public_path('images/products'), $imageName);
        
        // Simpan path relatif ke database
        $validated['image'] = 'images/products/' . $imageName;
    }

    // Simpan produk dengan shop_id
    $validated['shop_id'] = $shop->id;
    Product::create($validated);
}
```

#### Update Gambar Saat Edit Product (Line 220-260)

```php
public function updateProduct(Request $request, Product $product)
{
    // Validasi
    $validated = $request->validate([
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        // ... field lainnya
    ]);

    // Handle upload gambar baru
    if ($request->hasFile('image')) {
        // Hapus gambar lama
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        // Upload gambar baru
        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/products'), $imageName);
        $validated['image'] = 'images/products/' . $imageName;
    }

    $product->update($validated);
}
```

#### Delete Gambar Saat Hapus Product (Line 280-300)

```php
public function destroyProduct(Product $product)
{
    // Hapus file gambar dari server
    if ($product->image && file_exists(public_path($product->image))) {
        unlink(public_path($product->image));
    }

    // Hapus produk dari database
    $product->delete();
}
```

---

## 🎨 Form Upload

### Create Product Form
**File:** `resources/views/seller/products/create.blade.php`

```html
<form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <!-- Input file gambar -->
    <div class="mb-3">
        <label for="image" class="form-label">Product Image</label>
        <input type="file" 
               class="form-control @error('image') is-invalid @enderror" 
               id="image" 
               name="image" 
               accept="image/*">
        <small class="text-muted">Accepted formats: JPG, PNG, GIF (Max: 2MB)</small>
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <!-- Preview gambar -->
    <div class="mb-3">
        <div id="imagePreview" style="display: none;">
            <label class="form-label">Image Preview:</label>
            <div>
                <img id="preview" src="" alt="Preview" 
                     style="max-width: 200px; max-height: 200px; 
                            border: 1px solid #ddd; padding: 5px; 
                            border-radius: 5px;">
            </div>
        </div>
    </div>
    
    <!-- Field lainnya... -->
    
    <button type="submit" class="btn btn-primary">Add Product</button>
</form>

<!-- JavaScript untuk preview -->
<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
</script>
```

**Fitur Form:**
- ✅ `enctype="multipart/form-data"` untuk upload file
- ✅ Validasi error message
- ✅ Preview gambar sebelum upload
- ✅ Accept hanya file gambar
- ✅ Info format dan ukuran maksimal

---

## 🖼️ Menampilkan Gambar

### Di View Blade

```blade
<!-- Jika ada gambar -->
@if($product->image)
    <img src="{{ asset($product->image) }}" 
         alt="{{ $product->name }}" 
         class="img-fluid">
@else
    <!-- Placeholder jika tidak ada gambar -->
    <img src="{{ asset('images/no-image.png') }}" 
         alt="No Image" 
         class="img-fluid">
@endif
```

### Path Gambar di Database

Format yang disimpan: `images/products/1731218345_6730a1b9c4d2e.jpg`

- **Tidak** dimulai dengan `/`
- **Tidak** dimulai dengan `public/`
- Langsung: `images/products/filename.jpg`

Saat ditampilkan dengan `asset()`, Laravel otomatis menambahkan base URL:
```
asset('images/products/file.jpg') 
→ http://localhost/images/products/file.jpg
```

---

## 🔐 Validasi Upload

### Rules Validasi

```php
'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
```

**Penjelasan:**
- `nullable` - Gambar opsional (tidak wajib)
- `image` - Harus file gambar
- `mimes:jpeg,png,jpg,gif` - Format yang diizinkan
- `max:2048` - Maksimal 2MB (2048 KB)

### Error Messages

Jika validasi gagal, akan muncul error:
- File terlalu besar: "The image must not be greater than 2048 kilobytes."
- Format salah: "The image must be a file of type: jpeg, png, jpg, gif."

---

## 📊 Database Schema

### Tabel Products

```sql
CREATE TABLE products (
    id BIGINT PRIMARY KEY,
    shop_id BIGINT NULL,
    category_id BIGINT,
    name VARCHAR(255),
    description TEXT,
    price DECIMAL(10,2),
    stock INT,
    image VARCHAR(255) NULL,  ← Path gambar disimpan di sini
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Kolom `image`:**
- Type: `VARCHAR(255)`
- Nullable: `YES`
- Contoh value: `images/products/1731218345_6730a1b9c4d2e.jpg`

---

## 🎯 Cara Menggunakan

### Untuk Seller

#### 1. Tambah Produk Baru

1. Login sebagai seller
2. Buka menu **Products** → **Add Product**
3. Isi form produk:
   - Category
   - Product Name
   - Description
   - Price
   - Stock
   - **Upload Image** ← Klik dan pilih gambar
4. Preview gambar akan muncul otomatis
5. Klik **Add Product**
6. Gambar akan disimpan ke `public/images/products/`

#### 2. Edit Produk

1. Buka **Products** → Klik **Edit** pada produk
2. Form akan menampilkan gambar lama (jika ada)
3. Untuk ganti gambar:
   - Pilih gambar baru
   - Gambar lama akan otomatis dihapus
   - Gambar baru akan diupload
4. Klik **Update Product**

#### 3. Hapus Produk

1. Buka **Products** → Klik **Delete** pada produk
2. Konfirmasi penghapusan
3. Produk dan gambarnya akan dihapus dari server

---

## 🧪 Testing Upload

### Test Manual

1. **Login sebagai seller:**
   ```
   Email: seller.jakarta@example.com
   Password: password
   ```

2. **Buka Add Product:**
   ```
   URL: http://localhost/seller/products/create
   ```

3. **Upload gambar test:**
   - Pilih gambar JPG/PNG (max 2MB)
   - Lihat preview muncul
   - Submit form

4. **Cek hasil:**
   - Gambar tersimpan di `public/images/products/`
   - Path tersimpan di database
   - Gambar tampil di list produk

### Test dengan Seeder

Seeder sudah include path gambar:

```php
Product::create([
    'name' => 'Termometer Digital',
    'image' => 'products/thermometer.jpg',  // Path gambar
    // ... field lainnya
]);
```

**Note:** Gambar di seeder hanya path, file fisik tidak ada. Untuk testing lengkap, upload manual via form.

---

## 🐛 Troubleshooting

### Gambar Tidak Muncul

**Problem:** Gambar diupload tapi tidak tampil di halaman

**Solusi:**
1. Cek path di database:
   ```sql
   SELECT image FROM products WHERE id = 1;
   ```
   Harus: `images/products/filename.jpg`
   
2. Cek file ada di server:
   ```
   public/images/products/filename.jpg
   ```

3. Cek permission folder:
   ```bash
   # Windows (PowerShell)
   icacls public\images\products
   
   # Harus bisa write
   ```

4. Cek di view menggunakan `asset()`:
   ```blade
   <img src="{{ asset($product->image) }}">
   ```

### Upload Gagal

**Problem:** Error saat upload gambar

**Kemungkinan Penyebab:**

1. **File terlalu besar**
   - Max: 2MB
   - Solusi: Resize gambar atau ubah validasi

2. **Format tidak didukung**
   - Allowed: JPG, PNG, GIF
   - Solusi: Convert gambar ke format yang didukung

3. **Folder tidak ada**
   - Solusi: Buat folder manual
   ```bash
   mkdir public/images/products
   ```

4. **Permission denied**
   - Solusi: Set permission folder
   ```bash
   # Windows: Pastikan folder bisa write
   # Linux: chmod 755 public/images/products
   ```

5. **Form tidak ada enctype**
   - Solusi: Tambahkan `enctype="multipart/form-data"` di form tag

### Gambar Lama Tidak Terhapus

**Problem:** Saat update/delete, gambar lama masih ada di folder

**Cek:**
1. Path di database benar?
2. File exists check berfungsi?
3. Permission folder untuk delete?

**Debug:**
```php
// Di controller
if ($product->image && file_exists(public_path($product->image))) {
    \Log::info('Deleting: ' . public_path($product->image));
    unlink(public_path($product->image));
} else {
    \Log::warning('File not found: ' . public_path($product->image));
}
```

---

## 📝 Best Practices

### 1. Nama File Unik

✅ **Good:**
```php
$imageName = time() . '_' . uniqid() . '.' . $extension;
// Result: 1731218345_6730a1b9c4d2e.jpg
```

❌ **Bad:**
```php
$imageName = $request->file('image')->getClientOriginalName();
// Result: photo.jpg (bisa duplicate!)
```

### 2. Validasi Ketat

```php
'image' => [
    'nullable',
    'image',                              // Must be image
    'mimes:jpeg,png,jpg,gif',            // Allowed formats
    'max:2048',                          // Max 2MB
    'dimensions:min_width=100,min_height=100', // Min size
]
```

### 3. Hapus Gambar Lama

Selalu hapus gambar lama saat update/delete:

```php
if ($product->image && file_exists(public_path($product->image))) {
    unlink(public_path($product->image));
}
```

### 4. Placeholder Image

Sediakan gambar default jika produk tidak ada gambar:

```blade
@if($product->image)
    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
@else
    <img src="{{ asset('images/no-image.png') }}" alt="No Image">
@endif
```

### 5. Optimize Gambar

Sebelum upload, resize gambar untuk performa:

```php
// Install: composer require intervention/image
use Intervention\Image\Facades\Image;

$image = Image::make($request->file('image'))
    ->resize(800, 800, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    })
    ->save(public_path('images/products/' . $imageName));
```

---

## 🎉 Summary

**Status Upload Gambar:** ✅ **READY TO USE**

**Yang Sudah Dikonfigurasi:**
- ✅ Folder `public/images/products` sudah ada
- ✅ Controller sudah handle upload/update/delete
- ✅ Form sudah ada `enctype="multipart/form-data"`
- ✅ Validasi gambar sudah lengkap
- ✅ Preview gambar sebelum upload
- ✅ Auto delete gambar lama saat update/delete
- ✅ Path disimpan dengan format benar

**Cara Test:**
1. Login sebagai seller: `seller.jakarta@example.com` / `password`
2. Buka: `/seller/products/create`
3. Upload gambar produk
4. Cek gambar tersimpan di `public/images/products/`
5. Cek gambar tampil di list produk

**Selesai!** 🎊
