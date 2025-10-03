# Panduan Mengakses Export BOQ dengan Total Summary Sheet

## 📍 **Lokasi Fitur Export**

### 🔗 **URL Akses:**

```
/admin/boq-actuals/summary
```

### 🧭 **Cara Akses melalui Navigasi:**

#### **Metode 1: Melalui Sidebar Menu**

1. Login ke halaman admin
2. Di sidebar sebelah kiri, cari section **"Material Management"**
3. Klik **"Summary Material"**
    - Icon: 📊 (chart-pie)
    - Menu akan highlight dengan background merah saat aktif

#### **Metode 2: Melalui Top Navigation**

1. Login ke halaman admin
2. Di navigation bar atas, cari menu **"Summary"**
3. Klik pada menu tersebut

#### **Metode 3: Direct URL**

```
https://yourdomain.com/admin/boq-actuals/summary
```

## 📊 **Halaman Summary Material**

### **Header Informasi:**

-   **Title:** "Summary Material"
-   **Subtitle:** "Analisis pemakaian material berdasarkan proyek dan BOQ Actual"
-   **Total Material Counter:** Menampilkan jumlah total material

### **Quick Actions Tersedia:**

1. **"Kelola BOQ Actual"** - Button hijau untuk manage BOQ data
2. **"Tambah BOQ Actual"** - Button biru untuk add new BOQ
3. **"Export Excel Matrix"** - Button orange untuk export (⭐ **INI YANG KITA CARI!**)

## 🚀 **Cara Export BOQ dengan Total Summary:**

### **Step by Step:**

1. **Akses Halaman Summary:**

    - Pergi ke `/admin/boq-actuals/summary`
    - Atau gunakan navigasi sidebar → "Summary Material"

2. **Apply Filter (Optional):**

    - Klik "Tampilkan Filter" jika ingin filter data
    - Filter tersedia:
        - Project
        - Sub Project
        - Cluster
        - Hide No Data
    - Klik "Filter" untuk apply

3. **Click Export Button:**

    - Cari button **"Export Excel Matrix"** (warna orange)
    - Icon download di sebelah kiri text
    - Button hanya muncul jika ada data (count > 0)

4. **Download File:**
    - File Excel akan otomatis download
    - Format nama: `BOQ_Summary_Matrix_[timestamp].xlsx`
    - File berisi **2 sheets**:
        - **Sheet 1:** "BOQ Summary Matrix" - Matrix detail per DN/cluster
        - **Sheet 2:** "Total Summary" - Agregasi total per material (BARU!)

## 🎯 **Yang Didapat Setelah Export:**

### **Sheet 1: "BOQ Summary Matrix"**

-   Matrix detail dengan breakdown per DN/cluster
-   Headers dengan project information
-   Data detail per lokasi

### **Sheet 2: "Total Summary"** ⭐ **NEW!**

-   **Format sesuai referensi gambar:**
    -   Daftar material di kolom kiri
    -   TOTAL DO, TOTAL TERPAKAI, TOTAL BOQ ACTUAL, **TOTAL SISA**
    -   TIDAK ada kolom TOTAL SELISIH dan TOTAL POTONGAN
-   **Styling:**
    -   Headers hijau dengan project info
    -   BOQ Actual columns highlighted kuning
    -   TOTAL SISA dengan background putih yang jelas
    -   Borders dan formatting professional

## 🔍 **Route Information untuk Developer:**

```php
// Route untuk halaman summary
Route::get('/summary', [BOQActualController::class, 'summary'])
    ->name('boq-actuals.summary');

// Route untuk export
Route::get('/summary/export', [BOQActualController::class, 'exportSummary'])
    ->name('boq-actuals.export-summary');
```

## 💡 **Tips Penggunaan:**

1. **Filter Data:** Gunakan filter untuk export data spesifik project/cluster
2. **Check Data Count:** Pastikan ada data sebelum export (button export hanya muncul jika count > 0)
3. **File Size:** Filter data jika dataset sangat besar untuk performa optimal
4. **Browser Compatibility:** Pastikan browser mendukung auto-download files

## ✅ **Verification Checklist:**

-   [ ] Akses halaman `/admin/boq-actuals/summary`
-   [ ] Lihat button "Export Excel Matrix" (orange)
-   [ ] Klik export dan download file
-   [ ] Buka file Excel
-   [ ] Verifikasi ada 2 sheets: "BOQ Summary Matrix" dan "Total Summary"
-   [ ] Check kolom TOTAL SISA di sheet "Total Summary" memiliki data

---

**🎉 SEKARANG ANDA BISA MENGAKSES EXPORT BOQ DENGAN TOTAL SUMMARY SHEET!**

**Lokasi: Admin → Summary Material → Export Excel Matrix**
