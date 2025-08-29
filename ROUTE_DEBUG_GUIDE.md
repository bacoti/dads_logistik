# Route Debug Test

## Test Routes dengan Command

1. **Clear semua cache:**

```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

2. **Test route exists:**

```bash
php artisan route:list | findstr "po-materials"
```

3. **Jika masih error, coba:**

```bash
php artisan route:list --path="admin/po-materials"
```

## Test dengan Browser

1. **Login sebagai admin**
2. **Akses:** `/admin/po-materials`
3. **Pilih satu PO Material** dan klik "Lihat Detail"
4. **Buka Developer Tools (F12)**
5. **Klik tombol "Setujui"**
6. **Lihat Network tab** - seharusnya ada request ke:
    - URL: `/admin/po-materials/{id}/update-status`
    - Method: POST (with \_method: PATCH)

## Manual Test Route

Buat file `test_manual_route.php` dengan konten:

```php
<?php
// Test manual route
echo "Testing route manually...\n";

$baseUrl = "http://localhost:8000"; // Ganti dengan URL Anda
$poMaterialId = 1; // ID PO Material yang ada

$url = $baseUrl . "/admin/po-materials/" . $poMaterialId . "/update-status";
echo "URL to test: " . $url . "\n";

// Test dengan curl (jika tersedia)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "HTTP Code: " . $httpCode . "\n";
if ($httpCode === 419) {
    echo "CSRF Token error - ini normal untuk test manual\n";
} else if ($httpCode === 404) {
    echo "Route not found - ini masalahnya!\n";
} else {
    echo "Route exists (code: $httpCode)\n";
}

curl_close($ch);
?>
```

## Alternative Solution - Simple Form

Jika JavaScript masih bermasalah, gunakan form HTML biasa:

```html
<!-- Di file show.blade.php, ganti tombol dengan form ini -->
<form
    method="POST"
    action="{{ route('admin.po-materials.update-status', $poMaterial) }}"
    style="display: inline;"
>
    @csrf @method('PATCH')
    <input type="hidden" name="status" value="approved" />
    <button
        type="submit"
        class="btn btn-success"
        onclick="return confirm('Yakin mau approve?')"
    >
        Setujui
    </button>
</form>

<form
    method="POST"
    action="{{ route('admin.po-materials.update-status', $poMaterial) }}"
    style="display: inline;"
>
    @csrf @method('PATCH')
    <input type="hidden" name="status" value="rejected" />
    <button
        type="submit"
        class="btn btn-danger"
        onclick="return confirm('Yakin mau reject?')"
    >
        Tolak
    </button>
</form>
```

## Debug Steps

1. ✅ Route sudah ada di `routes/web.php`
2. ✅ Controller method `updateStatus` sudah ada
3. ✅ Middleware `role:admin` sudah terdaftar
4. ❓ **NEXT:** Test apakah route ter-load dengan benar

**Silakan coba langkah-langkah di atas dan laporkan hasilnya!**
