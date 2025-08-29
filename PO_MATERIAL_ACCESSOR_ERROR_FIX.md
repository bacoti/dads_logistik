# PO Material - Perbaikan Error BadMethodCallException

## Masalah yang Ditemukan

Error `BadMethodCallException: Call to undefined method App\Models\PoMaterial::getStatusBadge()` terjadi ketika mengakses halaman PO Materials.

## Penyebab

**Kesalahan penggunaan accessor method** di Blade view. Di Laravel, accessor harus dipanggil sebagai property, bukan method.

### ❌ SALAH:

```blade
{!! $poMaterial->getStatusBadge() !!}
{!! $poMaterial->getFormattedQuantity() !!}
```

### ✅ BENAR:

```blade
{!! $poMaterial->status_badge !!}
{{ $poMaterial->formatted_quantity }}
```

## File yang Diperbaiki

### 1. PO User Views

-   ✅ `resources/views/po/po-materials/index.blade.php` - Line 130
-   ✅ `resources/views/po/po-materials/edit.blade.php` - Line 31
-   ✅ `resources/views/po/po-materials/show.blade.php` - Line 34

### 2. Admin Views

-   ✅ `resources/views/admin/po-materials/index.blade.php` - Line 193
-   ✅ `resources/views/admin/po-materials/show.blade.php` - Line 60

## Penjelasan Accessor di Laravel

**Model PoMaterial** memiliki accessor:

```php
public function getStatusBadgeAttribute()
{
    // Return HTML badge
}

public function getFormattedQuantityAttribute()
{
    return number_format($this->quantity, 2) . ' ' . $this->unit;
}
```

**Cara Penggunaan di Blade:**

-   `getStatusBadgeAttribute()` → `$model->status_badge`
-   `getFormattedQuantityAttribute()` → `$model->formatted_quantity`

## Status Setelah Perbaikan

✅ **PO Materials Index** - Dapat diakses tanpa error
✅ **PO Materials Show** - Status badge tampil dengan benar
✅ **PO Materials Edit** - Status badge tampil dengan benar
✅ **Admin PO Materials** - Semua view berfungsi normal

## Testing

### Test Accessor Method:

```bash
php artisan tinker --execute="
\$po = App\Models\PoMaterial::first();
echo 'Status Badge: ' . \$po->status_badge . PHP_EOL;
echo 'Formatted Quantity: ' . \$po->formatted_quantity . PHP_EOL;
"
```

### Test View Access:

1. **Login sebagai user PO** (Marco - `marco@gmail.com`)
2. **Akses**: `/po/po-materials` ✅
3. **Akses**: `/po/po-materials/create` ✅
4. **Test form submission** ✅

## Status Badge Output

Berdasarkan status PO Material:

-   `pending` → 🟡 **Menunggu** (Yellow badge)
-   `approved` → 🟢 **Disetujui** (Green badge)
-   `rejected` → 🔴 **Ditolak** (Red badge)
-   `active` → 🟢 **Aktif** (Green badge)
-   `completed` → 🔵 **Selesai** (Blue badge)
-   `cancelled` → 🔴 **Dibatalkan** (Red badge)

Sekarang halaman PO Materials dapat diakses dengan normal tanpa error!
