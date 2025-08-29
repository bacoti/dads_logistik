📋 PANDUAN LENGKAP MENGGANTI LOGO PERUSAHAAN PT DADS LOGISTIK

🎯 LANGKAH-LANGKAH:

1. # PERSIAPAN FILE LOGO

    Siapkan file logo perusahaan dalam format:

    - PNG (direkomendasikan untuk transparansi)
    - JPG (untuk logo tanpa transparansi)
    - SVG (untuk vector graphics)

2. # LOKASI PENYIMPANAN FILE

    Simpan semua file logo di folder:
    📁 a:\Kampus\PKL I\PROJECT LOGISTIK\pt-dads-logistik\public\images\logos\

    Nama file yang digunakan:
    • logo-company.png - Logo utama (40x40px atau 50x50px, persegi)
    • logo-white.png - Logo versi putih untuk background gelap
    • logo-horizontal.png - Logo horizontal untuk header (200x60px)
    • favicon.ico - Icon browser tab (32x32px atau 16x16px)
    • favicon-32x32.png - Favicon PNG 32x32
    • favicon-16x16.png - Favicon PNG 16x16

3. # UKURAN YANG DISARANKAN

    • Logo sidebar: 40x40px - 50x50px (square/persegi)
    • Logo header: 200x60px - 300x100px (landscape)
    • Favicon: 32x32px, 16x16px

4. # LOKASI LOGO DALAM APLIKASI

    Logo akan muncul di:
    ✅ Sidebar kiri (sudah diupdate)
    ✅ Browser tab/favicon (sudah diupdate)
    📋 Login page (jika diperlukan)
    📋 Report headers (jika diperlukan)

5. # FILE YANG SUDAH DIUPDATE

    ✅ resources/views/layouts/sidebar.blade.php
    ✅ resources/views/layouts/app.blade.php

6. # CARA TESTING

    Setelah menyimpan file logo:

    1. Clear cache: php artisan view:clear
    2. Refresh browser (Ctrl+F5)
    3. Cek sidebar dan browser tab

7. # FALLBACK SYSTEM

    Jika logo tidak ditemukan, sistem akan menampilkan icon truck default.

8. # TIPS TAMBAHAN
    • Gunakan background transparan untuk PNG
    • Pastikan logo terlihat jelas pada background merah
    • Test logo pada berbagai ukuran layar
    • Backup logo asli sebelum resize

# 🔧 TROUBLESHOOTING:

• Logo tidak muncul? Cek nama file dan lokasi folder
• Logo pecah/blur? Gunakan ukuran yang tepat
• Logo tidak transparan? Gunakan format PNG
• Browser belum update? Clear cache browser (Ctrl+Shift+R)

# 📞 BANTUAN:

Jika mengalami kesulitan, pastikan:

1. File logo sudah tersimpan di folder yang benar
2. Nama file sesuai dengan yang ada di kode
3. Ukuran file tidak terlalu besar (max 500KB)
4. Format file didukung (PNG, JPG, SVG)

Happy coding! 🚀
