# Database Seeders Documentation

## Daftar Seeder yang Telah Dibuat

### 1. ComprehensiveMasterDataSeeder

Seeder ini mengisi data master yang komprehensif untuk sistem logistik:

-   **15 Vendors** - Perusahaan penyedia material
-   **10 Main Projects** - Proyek utama dengan kode unik
-   **10 Sub Projects** - Sub proyek (1 per proyek utama)
-   **15 Categories** - Kategori material yang terhubung dengan sub project
-   **20 Materials** - Material dengan unit dan kategori yang sesuai

### 2. TransactionReceivingSeeder

Seeder untuk transaksi penerimaan material:

-   **250 Transaksi Penerimaan** (`type: 'penerimaan'`)
-   Setiap transaksi memiliki 1-5 material
-   Transaksi dalam rentang waktu 6 bulan terakhir
-   Lokasi tersebar di 20 kota di Indonesia
-   Site ID berurutan SITE-0001 hingga SITE-0250

### 3. AllTransactionTypesSeeder

Seeder untuk variasi jenis transaksi lainnya:

-   **150 Transaksi** dengan berbagai tipe:
    -   Penerimaan (dengan vendor)
    -   Pengambilan (tanpa vendor)
    -   Pengembalian (tanpa vendor)
    -   Peminjaman (tanpa vendor)
-   Quantity disesuaikan berdasarkan tipe transaksi
-   Notes yang kontekstual sesuai jenis transaksi
-   Site ID lanjutan dari SITE-0251 hingga SITE-0400

## Total Data yang Dihasilkan

### Master Data:

-   15 Vendors
-   10 Projects
-   10 Sub Projects
-   15 Categories
-   20 Materials
-   6 Users (1 admin + 5 test users)

### Transaction Data:

-   400 Total Transactions
-   ~250 Penerimaan transactions
-   ~150 Mixed type transactions (pengambilan, pengembalian, peminjaman)
-   800-1200 Transaction Details (tergantung random material per transaksi)

## Cara Menjalankan

```bash
# Jalankan semua seeder
php artisan migrate:fresh --seed

# Atau jalankan seeder spesifik
php artisan db:seed --class=ComprehensiveMasterDataSeeder
php artisan db:seed --class=TransactionReceivingSeeder
php artisan db:seed --class=AllTransactionTypesSeeder
```

## Catatan Penting

1. **Relasi Data**: Semua data sudah saling berelasi dengan benar
2. **Faker Indonesia**: Menggunakan locale 'id_ID' untuk data yang sesuai dengan Indonesia
3. **Realistic Data**: Quantity dan notes disesuaikan dengan jenis transaksi
4. **Date Range**: Semua transaksi dalam rentang 6 bulan terakhir
5. **Vendor Logic**: Hanya transaksi 'penerimaan' yang memiliki vendor_id

## File Location

-   `database/seeders/ComprehensiveMasterDataSeeder.php`
-   `database/seeders/TransactionReceivingSeeder.php`
-   `database/seeders/AllTransactionTypesSeeder.php`
-   `database/seeders/DatabaseSeeder.php` (updated)
