<?php
/**
 * Updated Storage Check untuk Hostinger dengan struktur: public_html + LOGISTIK-DADS
 */

echo "<!DOCTYPE html><html><head><title>Storage Check</title></head><body>";
echo "<h2>🔍 Storage & Upload Status Check (Updated)</h2>";

// Berdasarkan struktur index.php
$currentDir = __DIR__;  // public_html
$laravelDir = dirname(dirname($currentDir)) . '/LOGISTIK-DADS';
$storageAppPublic = $laravelDir . '/storage/app/public';
$publicStorage = $currentDir . '/storage';

// Function untuk mengecek env variables
function getEnvValue($file, $key, $default = 'NOT SET') {
    if (file_exists($file)) {
        $envContent = file_get_contents($file);
        if (preg_match("/^$key=(.*)$/m", $envContent, $matches)) {
            return trim($matches[1]);
        }
    }
    return $default;
}

$envFile = $laravelDir . '/.env';
$appUrl = getEnvValue($envFile, 'APP_URL', 'NOT SET');
$filesystemDisk = getEnvValue($envFile, 'FILESYSTEM_DISK', 'local');

echo "<h3>Configuration:</h3>";
echo "<p><strong>APP_URL:</strong> " . htmlspecialchars($appUrl) . "</p>";
echo "<p><strong>Filesystem Disk:</strong> " . htmlspecialchars($filesystemDisk) . "</p>";

echo "<h3>Path Information:</h3>";
echo "<p><strong>Current Directory (public_html):</strong> " . htmlspecialchars($currentDir) . "</p>";
echo "<p><strong>Laravel Directory:</strong> " . htmlspecialchars($laravelDir) . "</p>";
echo "<p><strong>Storage App Public:</strong> " . htmlspecialchars($storageAppPublic) . "</p>";
echo "<p><strong>Public Storage (target):</strong> " . htmlspecialchars($publicStorage) . "</p>";

// Check directories
echo "<h3>Directory Status:</h3>";

if (is_dir($laravelDir)) {
    echo "<p>✅ Laravel directory found</p>";
} else {
    echo "<p>❌ Laravel directory not found</p>";
}

if (is_dir($storageAppPublic)) {
    echo "<p>✅ Storage app/public exists</p>";
} else {
    echo "<p>❌ Storage app/public not found</p>";
}

// Check public storage status
if (is_link($publicStorage)) {
    echo "<p>✅ Symbolic link exists: " . htmlspecialchars(readlink($publicStorage)) . "</p>";
} elseif (is_dir($publicStorage)) {
    echo "<p>📁 Directory exists (not symlink)</p>";
} else {
    echo "<p>❌ Public storage not found</p>";
}

// Check permissions
echo "<h3>Permissions:</h3>";
if (is_dir($storageAppPublic)) {
    echo "<p>Storage app/public readable: " . (is_readable($storageAppPublic) ? "✅" : "❌") . "</p>";
    echo "<p>Storage app/public writable: " . (is_writable($storageAppPublic) ? "✅" : "❌") . "</p>";
}

if (is_dir($publicStorage)) {
    echo "<p>Public storage readable: " . (is_readable($publicStorage) ? "✅" : "❌") . "</p>";
}

// Check upload folders
$uploadFolders = ['monthly-reports', 'loss-reports', 'transaction-proofs', 'po-transports'];
echo "<h3>Upload Folders:</h3>";

if (is_dir($storageAppPublic)) {
    foreach ($uploadFolders as $folder) {
        $folderPath = $storageAppPublic . '/' . $folder;
        if (is_dir($folderPath)) {
            $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));
            echo "<p>✅ $folder ($fileCount files)</p>";
        } else {
            echo "<p>❌ $folder not found</p>";
        }
    }
} else {
    echo "<p>⚠️ Cannot check upload folders - storage app/public not found</p>";
}

// Test file access
echo "<h3>File Access Test:</h3>";
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];

if (is_dir($storageAppPublic)) {
    $testFile = $storageAppPublic . '/test.txt';
    if (file_put_contents($testFile, 'Storage test file - ' . date('Y-m-d H:i:s'))) {
        $testUrl = $currentUrl . '/storage/test.txt';
        echo "<p>✅ Test file created</p>";
        echo "<p>Try accessing: <a href='" . htmlspecialchars($testUrl) . "' target='_blank'>Test File</a></p>";
    } else {
        echo "<p>❌ Cannot create test file</p>";
    }
}

// Troubleshooting tips
echo "<hr>";
echo "<h3>📋 Troubleshooting:</h3>";

if (!is_dir($laravelDir)) {
    echo "<p>❌ Laravel directory tidak ditemukan. Periksa struktur hosting Anda.</p>";
} elseif (!is_dir($storageAppPublic)) {
    echo "<p>⚠️ Storage app/public tidak ada. Jalankan: <code>mkdir -p " . htmlspecialchars($storageAppPublic) . "</code></p>";
} elseif (!is_dir($publicStorage) && !is_link($publicStorage)) {
    echo "<p>🔧 <a href='simple_storage.php'>Klik untuk membuat storage link</a></p>";
} else {
    echo "<p>✅ Semua tampak normal. Test upload file di aplikasi Laravel Anda.</p>";
}

echo "<h4>Manual Commands (jika perlu):</h4>";
echo "<code>ln -sf " . htmlspecialchars($storageAppPublic) . " " . htmlspecialchars($publicStorage) . "</code><br>";
echo "<code>chmod -R 755 " . htmlspecialchars($storageAppPublic) . "</code>";

echo "</body></html>";
?>
