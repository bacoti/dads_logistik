# PO Material Approval Troubleshooting Guide

## Langkah-langkah Debugging

### 1. Buka Browser Console (F12)

Ketika Anda mengklik tombol "Setujui" di halaman detail PO Material, periksa Console untuk melihat log berikut:

**Expected Console Logs:**

```
=== PO APPROVAL DEBUG START ===
Change status called with: {poMaterialId: "X", status: "approved"}
Status text: menyetujui
User confirmed action
Approve button disabled
Route template: http://your-domain/admin/po-materials/:id/update-status
Final route URL: http://your-domain/admin/po-materials/X/update-status
CSRF token: [token-value]
Form elements:
- Action: http://your-domain/admin/po-materials/X/update-status
- Method: POST
- Children count: 3 or 4
- _token: [token-value]
- _method: PATCH
- status: approved
- notes: [if provided]
Form appended to body
Submitting form...
=== PO APPROVAL DEBUG END ===
```

### 2. Periksa Network Tab

Di Developer Tools, buka tab "Network" dan coba approve PO Material:

-   Seharusnya ada request POST ke `/admin/po-materials/{id}/update-status`
-   Status response seharusnya 200 atau 302 (redirect)
-   Jika ada error 4xx atau 5xx, catat pesan errornya

### 3. Periksa Laravel Log

Cek file `storage/logs/laravel.log` untuk melihat log dari controller:

```
[timestamp] local.INFO: PO Material update status called {"po_material_id":X,"current_status":"pending","request_data":{...},"user_id":Y}
[timestamp] local.INFO: PO Material status updated {"po_material_id":X,"old_status":"pending","new_status":"approved",...}
```

### 4. Kemungkinan Masalah dan Solusi

#### A. JavaScript Error

**Gejala**: Tidak ada log di console, tombol tidak merespons
**Solusi**:

-   Cek ada error JavaScript di console
-   Pastikan function `changeStatus` terdefinisi
-   Refresh halaman dan coba lagi

#### B. CSRF Token Issue

**Gejala**: Request POST gagal dengan error 419 (CSRF Token Mismatch)
**Solusi**:

-   Refresh halaman untuk mendapat token baru
-   Cek apakah `@csrf` ada di form

#### C. Route/Permission Issue

**Gejala**: Error 404 (Not Found) atau 403 (Forbidden)
**Solusi**:

-   Pastikan user login sebagai admin (bukan PO user)
-   Cek route dengan: `php artisan route:list | grep po-materials`

#### D. Database/Model Issue

**Gejala**: Request berhasil tapi status tidak berubah
**Solusi**:

-   Cek log Laravel untuk error database
-   Pastikan kolom `status` ada di tabel `po_materials`
-   Cek apakah ada validation error

### 5. Quick Test Commands

#### Test Route

```bash
php artisan route:list | grep "po-materials.*update-status"
```

#### Test Database

```bash
php artisan tinker
>>> $po = App\Models\PoMaterial::find(1);
>>> $po->status;
>>> $po->update(['status' => 'approved']);
>>> $po->fresh()->status;
```

#### Test Permissions

```bash
php artisan tinker
>>> $user = auth()->user();
>>> $user->role;  // should be 'admin'
```

### 6. Manual Testing Steps

1. **Login sebagai admin** (bukan sebagai user PO)
2. **Akses halaman detail PO Material** yang statusnya 'pending'
3. **Buka Developer Tools** (F12) dan buka tab Console
4. **Klik tombol "Setujui"**
5. **Konfirmasi dialog** yang muncul
6. **Periksa console logs** - harus muncul semua log debug
7. **Periksa Network tab** - harus ada request POST
8. **Tunggu redirect** - halaman harus refresh dengan pesan sukses
9. **Cek status PO Material** - harus berubah dari "Menunggu" ke "Disetujui"

### 7. File yang Sudah Diperbaiki

-   ✅ `resources/views/admin/po-materials/show.blade.php` - Enhanced JavaScript dengan debugging
-   ✅ `app/Http/Controllers/Admin/PoMaterialController.php` - Added logging dan validation

### 8. Jika Masih Tidak Berfungsi

Kirim screenshot dari:

1. Console logs (tab Console)
2. Network requests (tab Network)
3. Laravel log errors (storage/logs/laravel.log)

Dengan informasi ini saya bisa memberikan solusi yang lebih spesifik.
