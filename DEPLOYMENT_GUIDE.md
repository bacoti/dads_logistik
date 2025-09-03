# Panduan Deploy Excel Export Feature ke Hostinger

## File yang Perlu Diupload

### 1. Export Classes (Baru)

-   `app/Exports/LossReportsExport.php` ✅ BARU
-   `app/Exports/MfoRequestsExport.php` ✅ BARU
-   `app/Exports/ComprehensiveExport.php` ✅ BARU

### 2. Export Classes (Updated)

-   `app/Exports/TransactionsExport.php` ✅ DIPERBARUI
-   `app/Exports/MonthlyReportsExport.php` ✅ DIPERBARUI

### 3. Controllers (Updated)

-   `app/Http/Controllers/Admin/DashboardController.php` ✅ DIPERBARUI
-   `app/Http/Controllers/Admin/TransactionController.php` ✅ DIPERBARUI
-   `app/Http/Controllers/Admin/MonthlyReportController.php` ✅ DIPERBARUI
-   `app/Http/Controllers/Admin/LossReportController.php` ✅ DIPERBARUI
-   `app/Http/Controllers/Admin/MfoRequestController.php` ✅ DIPERBARUI

### 4. Routes (Updated)

-   `routes/web.php` ✅ DIPERBARUI

### 5. Views (Updated)

-   `resources/views/admin/transactions/index.blade.php` ✅ DIPERBARUI
-   `resources/views/admin/monthly-reports/index.blade.php` (PERLU UPDATE)
-   `resources/views/admin/loss-reports/index.blade.php` (PERLU UPDATE)
-   `resources/views/admin/mfo-requests/index.blade.php` (PERLU UPDATE)

## Cara Upload ke Hostinger

### Opsi 1: Menggunakan SCP (Secure Copy)

```bash
# Upload file individual
scp app/Exports/LossReportsExport.php username@your-server.com:/home/username/domains/yourdomain.com/public_html/app/Exports/

# Upload folder sekaligus
scp -r app/Exports/ username@your-server.com:/home/username/domains/yourdomain.com/public_html/app/
```

### Opsi 2: Menggunakan SFTP

```bash
sftp username@your-server.com
put app/Exports/LossReportsExport.php /home/username/domains/yourdomain.com/public_html/app/Exports/
```

### Opsi 3: Menggunakan File Manager Hostinger

1. Login ke hPanel Hostinger
2. Buka File Manager
3. Upload file satu per satu ke direktori yang sesuai

## Langkah Setelah Upload

### 1. Clear Cache di Server

```bash
ssh username@your-server.com
cd /home/username/domains/yourdomain.com/public_html
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 2. Set Permission (jika diperlukan)

```bash
chmod 755 app/Exports/
chmod 644 app/Exports/*.php
```

### 3. Test Export Feature

-   Akses website Anda
-   Coba fitur export di:
    -   `/admin/transactions`
    -   `/admin/monthly-reports`
    -   `/admin/loss-reports`
    -   `/admin/mfo-requests`

## Troubleshooting

### Jika Error "Class Not Found"

```bash
composer dump-autoload
```

### Jika Error Permission

```bash
chown -R username:username app/Exports/
```

### Jika Error Excel Package

```bash
composer install --no-dev --optimize-autoloader
```

## Fitur Export yang Tersedia

1. **Export Transaksi** - Dengan detail material dan quantity
2. **Export Laporan Bulanan** - Dengan status dan review
3. **Export Laporan Kehilangan** - Dengan kronologi dan dokumen
4. **Export Pengajuan MFO** - Dengan status approval
5. **Export Comprehensive** - Semua data dalam satu file Excel (multiple sheets)

## Format Excel

-   **Styling**: Header berwarna sesuai tema, borders, zebra striping
-   **Auto-size**: Kolom otomatis menyesuaikan lebar konten
-   **Data terstruktur**: Tanggal terformat Indonesia, status diterjemahkan
-   **Multiple sheets**: Export comprehensive menggunakan beberapa sheet
