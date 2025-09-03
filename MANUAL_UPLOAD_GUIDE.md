# Panduan Manual Upload ke Hostinger File Manager

## 1. File yang Harus Diupload (Copy Paste Manual)

### A. File Baru (Create New File di File Manager)

**1. app/Exports/LossReportsExport.php**

-   Buka File Manager Hostinger → app/Exports/
-   Create New File → LossReportsExport.php
-   Copy paste isi dari file lokal

**2. app/Exports/MfoRequestsExport.php**

-   Sama seperti di atas

**3. app/Exports/ComprehensiveExport.php**

-   Sama seperti di atas

### B. File yang Diupdate (Edit Existing File)

**1. app/Exports/TransactionsExport.php**

-   Buka file ini di File Manager
-   Replace semua isinya dengan versi yang sudah diupdate

**2. app/Exports/MonthlyReportsExport.php**

-   Sama seperti di atas

**3. app/Http/Controllers/Admin/DashboardController.php**

-   Edit file ini dan update bagian use statements dan method export

**4. routes/web.php**

-   Update bagian admin routes

**5. View Files:**

-   resources/views/admin/transactions/index.blade.php
-   resources/views/admin/monthly-reports/index.blade.php
-   resources/views/admin/loss-reports/index.blade.php
-   resources/views/admin/mfo-requests/index.blade.php

## 2. Langkah Setelah Upload

1. **Login SSH ke server:**

    ```bash
    ssh username@your-server.com
    ```

2. **Masuk ke direktori website:**

    ```bash
    cd /home/username/domains/yourdomain.com/public_html
    ```

3. **Clear cache:**

    ```bash
    php artisan route:clear
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    ```

4. **Optimize autoloader:**

    ```bash
    composer dump-autoload --optimize
    ```

5. **Test fitur export**

## 3. Alternative: Upload via ZIP

1. **Di komputer lokal:**

    - Buat folder bernama `excel_export_update`
    - Copy semua file yang diubah dengan struktur folder yang sama
    - Zip folder tersebut

2. **Upload ZIP ke server:**

    - Login File Manager Hostinger
    - Upload file ZIP ke root directory
    - Extract ZIP
    - Pindahkan file ke lokasi yang benar

3. **Cleanup:**
    - Hapus file ZIP dan folder temporary

## 4. Troubleshooting

**Jika error "Class not found":**

```bash
composer dump-autoload
```

**Jika error permission:**

```bash
chmod -R 755 app/Exports/
chown -R username:username app/Exports/
```

**Jika Laravel Excel error:**

```bash
composer require maatwebsite/excel
```

## 5. Test Export Feature

Setelah upload, test di URL berikut:

-   https://yourdomain.com/admin/transactions → Klik tombol "Export Excel"
-   https://yourdomain.com/admin/monthly-reports → Klik tombol "Export Excel"
-   https://yourdomain.com/admin/loss-reports → Klik tombol "Export Excel"
-   https://yourdomain.com/admin/mfo-requests → Klik tombol "Export Excel"

Export comprehensive (semua data) tersedia di dashboard admin.
