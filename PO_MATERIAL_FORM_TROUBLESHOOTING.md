# PO Material Form - Troubleshooting & Perbaikan

## Masalah yang Ditemukan

Form PO Material tidak merespons ketika tombol "Tambahkan" diklik, tidak ada feedback atau error yang muncul.

## Penyebab Kemungkinan dan Solusi

### 1. **Konflik Database Enum Status** ✅ DIPERBAIKI

**Masalah**: Migration mendefinisikan enum status `['active', 'completed', 'cancelled']` tapi controller mencoba menyimpan `'pending'`

**Solusi**:

-   Dibuat migration baru `2025_08_27_035000_update_po_materials_status_enum.php`
-   Update enum menjadi `['pending', 'approved', 'rejected', 'active', 'completed', 'cancelled']`
-   Default status menjadi `'pending'`

### 2. **Form Debugging & Validation** ✅ DIPERBAIKI

**Perbaikan yang dilakukan**:

-   Menambahkan `id="po-material-form"` pada form
-   Menambahkan loading state pada submit button
-   Menambahkan validasi JavaScript real-time
-   Menambahkan console.log untuk debugging
-   Menambahkan error handling yang lebih baik

### 3. **AJAX Route Sub-Projects**

**Route yang diperlukan**:

```php
Route::get('ajax/sub-projects', [PoMaterialController::class, 'getSubProjects'])->name('ajax.sub-projects');
```

## Langkah Troubleshooting

### Step 1: Cek Database Migration

```bash
php artisan migrate
```

### Step 2: Test Model PoMaterial

```bash
php artisan tinker --execute="
\$user = App\Models\User::where('email', 'marco@gmail.com')->first();
\$project = App\Models\Project::first();
\$testPO = App\Models\PoMaterial::create([
    'user_id' => \$user->id,
    'po_number' => 'PO-TEST-001',
    'supplier' => 'Test Supplier',
    'release_date' => today(),
    'location' => 'Jakarta',
    'project_id' => \$project->id,
    'description' => 'Test material',
    'quantity' => 100,
    'unit' => 'pcs',
    'status' => 'pending'
]);
echo 'Test PO created: ' . \$testPO->id;
"
```

### Step 3: Test Browser Console

1. Buka Developer Tools (F12)
2. Go to Console tab
3. Isi form PO Material
4. Klik submit dan lihat log:
    - "PO Material Form initialized"
    - "Form submitted"
    - "Form validation passed, submitting..."

### Step 4: Test Network Tab

1. Buka Developer Tools > Network tab
2. Submit form
3. Cari request ke `/po/po-materials` (POST)
4. Lihat status response dan error

## Testing dengan User Marco

**User Marco**: `marco@gmail.com` (Role: PO)

```bash
# Test dengan user Marco
php artisan tinker --execute="
\$marco = App\Models\User::where('email', 'marco@gmail.com')->first();
echo 'Marco found: ' . (\$marco ? \$marco->name . ' - Role: ' . \$marco->role : 'Not found');
"
```

## File yang Dimodifikasi

1. ✅ `database/migrations/2025_08_27_035000_update_po_materials_status_enum.php` (BARU)
2. ✅ `resources/views/po/po-materials/create.blade.php` (DIPERBAIKI)
    - Menambahkan form ID
    - Menambahkan loading state
    - Menambahkan validasi JavaScript
    - Menambahkan debugging console.log

## Kemungkinan Masalah Lain

### A. CSRF Token

-   Pastikan `@csrf` ada dalam form
-   Cek di Network tab apakah token dikirim

### B. Route Middleware

-   Pastikan user Marco memiliki akses ke route `po.*`
-   Cek middleware `role:po` di routes

### C. JavaScript Error

-   Cek Console untuk error JavaScript
-   Pastikan tidak ada conflict dengan library lain

### D. Server Error

-   Cek Laravel log: `storage/logs/laravel.log`
-   Cek web server error log

## Cara Test Manual

1. **Login sebagai Marco** (`marco@gmail.com`)
2. **Akses form PO**: `/po/po-materials/create`
3. **Isi semua field wajib**:
    - No. PO: PO-TEST-001
    - Supplier: Test Supplier
    - Tanggal Rilis: [Hari ini]
    - Lokasi: Jakarta
    - Project: [Pilih project]
    - Keterangan: Test material
    - Qty: 100
    - Satuan: pcs
4. **Buka Developer Tools** (F12)
5. **Klik "Simpan PO Material"**
6. **Periksa**:
    - Console log muncul
    - Button berubah ke "Menyimpan..."
    - Request POST muncul di Network tab
    - Redirect ke index atau error ditampilkan

## Expected Behavior Setelah Perbaikan

✅ **Button loading state**: Button disabled + text "Menyimpan..."
✅ **Console debugging**: Log muncul di browser console
✅ **Validation**: Alert jika field wajib kosong
✅ **AJAX sub-projects**: Sub project ter-load ketika project dipilih
✅ **Form submission**: Redirect ke index dengan success message atau error validation
