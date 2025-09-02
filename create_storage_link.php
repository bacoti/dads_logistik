<?php
/**
 * Script untuk membuat symbolic link storage secara manual
 * Upload script ini ke root domain hosting Hostinger
 * Akses melalui browser: yourdomain.com/create_storage_link.php
 */

echo "<!DOCTYPE html>";
echo "<html><head><title>Create Storage Link</title></head><body>";
echo "<h2>🔧 Create Storage Link</h2>";

// Path ke folder public_html (biasanya di Hostinger)
$documentRoot = $_SERVER['DOCUMENT_ROOT'];
$target = $documentRoot . '/storage';
$link = $documentRoot . '/../storage/app/public';

echo "<strong>Path Information:</strong><br>";
echo "Document Root: " . htmlspecialchars($documentRoot) . "<br>";
echo "Target: " . htmlspecialchars($target) . "<br>";
echo "Source: " . htmlspecialchars($link) . "<br><br>";

// Cek apakah symbolic link sudah ada
if (is_link($target)) {
    echo "✅ Symbolic link sudah ada: " . htmlspecialchars($target) . " -> " . htmlspecialchars(readlink($target)) . "<br>";
} else {
    // Cek apakah source folder ada
    if (!is_dir($link)) {
        echo "❌ Source folder tidak ditemukan: " . htmlspecialchars($link) . "<br>";
        echo "Mencoba membuat folder source...<br>";
        if (mkdir($link, 0755, true)) {
            echo "✅ Berhasil membuat folder source<br>";
        } else {
            echo "❌ Gagal membuat folder source<br>";
        }
    }

    // Buat symbolic link
    if (symlink($link, $target)) {
        echo "✅ Berhasil membuat symbolic link: " . htmlspecialchars($target) . " -> " . htmlspecialchars($link) . "<br>";
    } else {
        echo "❌ Gagal membuat symbolic link<br>";

        // Alternative: Copy files instead of symlink
        echo "Mencoba metode copy file...<br>";

        function copyDirectory($src, $dst) {
            if (!is_dir($src)) return false;

            $dir = opendir($src);
            @mkdir($dst, 0755, true);

            while(($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    if (is_dir($src . '/' . $file)) {
                        copyDirectory($src . '/' . $file, $dst . '/' . $file);
                    } else {
                        copy($src . '/' . $file, $dst . '/' . $file);
                    }
                }
            }
            closedir($dir);
            return true;
        }

        if (is_dir($link)) {
            if (copyDirectory($link, $target)) {
                echo "✅ Berhasil copy files ke public/storage<br>";
            } else {
                echo "❌ Gagal copy files<br>";
            }
        }
    }
}

// Cek permission folder
echo "<br><strong>Informasi Permission:</strong><br>";
if (is_dir($link)) {
    echo "Storage app/public: " . (is_readable($link) ? "✅ Readable" : "❌ Not readable") . "<br>";
} else {
    echo "Storage app/public: ❌ Folder tidak ada<br>";
}

if (is_dir($target)) {
    echo "Public storage: " . (is_readable($target) ? "✅ Readable" : "❌ Not readable") . "<br>";
} else {
    echo "Public storage: ❌ Folder tidak ada<br>";
}

// Test upload path dan buat folder yang dibutuhkan
$testDirs = [
    'monthly-reports',
    'loss-reports',
    'transaction-proofs',
    'po-transports'
];

echo "<br><strong>Cek Folder Upload:</strong><br>";
$baseDir = is_dir($target) ? $target : $link;

foreach ($testDirs as $dir) {
    $fullPath = $baseDir . '/' . $dir;
    if (!is_dir($fullPath)) {
        if (mkdir($fullPath, 0755, true)) {
            echo "✅ Membuat folder: " . htmlspecialchars($dir) . "<br>";
        } else {
            echo "❌ Gagal membuat folder: " . htmlspecialchars($dir) . "<br>";
        }
    } else {
        echo "✅ Folder sudah ada: " . htmlspecialchars($dir) . "<br>";
    }
}

// Buat test file
echo "<br><strong>Test File Creation:</strong><br>";
$testFile = $target . '/test.txt';
if (file_put_contents($testFile, 'Test file created at: ' . date('Y-m-d H:i:s'))) {
    $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    echo "✅ Test file berhasil dibuat<br>";
    echo "Akses test file: <a href='" . htmlspecialchars($currentUrl . "/storage/test.txt") . "' target='_blank'>Test File</a><br>";
} else {
    echo "❌ Gagal membuat test file<br>";
}

echo "<br>🎉 <strong>Selesai!</strong><br>";
echo "<p>Hapus file ini setelah selesai untuk keamanan.</p>";
echo "<p><a href='check_storage.php'>Cek status storage</a></p>";
echo "</body></html>";
?>
