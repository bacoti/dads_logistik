<?php
echo "<!DOCTYPE html><html><head><title>Storage File Inspector</title></head><body>";
echo "<h2>🔍 Storage File Inspector</h2>";

$currentDir = __DIR__;
$laravelDir = dirname(dirname($currentDir)) . '/LOGISTIK-DADS';
$storageAppPublic = $laravelDir . '/storage/app/public';
$targetStorage = $currentDir . '/storage';

echo "<h3>📁 Files in Storage App Public:</h3>";

function listDirectoryContents($dir, $prefix = '') {
    if (!is_dir($dir)) {
        echo "<p>❌ Directory not found: " . htmlspecialchars($dir) . "</p>";
        return;
    }

    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;

        $itemPath = $dir . '/' . $item;
        $relativePath = $prefix . $item;

        if (is_dir($itemPath)) {
            echo "<p>📁 " . htmlspecialchars($relativePath) . "/</p>";
            // Recursively list subdirectory contents
            listDirectoryContents($itemPath, $relativePath . '/');
        } else {
            $fileSize = filesize($itemPath);
            $fileSizeFormatted = $fileSize > 1024 ? round($fileSize/1024, 2) . ' KB' : $fileSize . ' B';
            $fileUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/storage/' . $relativePath;

            echo "<p>📄 " . htmlspecialchars($relativePath) . " (" . $fileSizeFormatted . ") ";
            echo "<a href='" . htmlspecialchars($fileUrl) . "' target='_blank' style='color: blue;'>[Test Link]</a></p>";
        }
    }
}

listDirectoryContents($storageAppPublic);

echo "<h3>🔗 Storage Link Status:</h3>";
if (is_link($targetStorage)) {
    echo "<p>✅ Symbolic link: " . readlink($targetStorage) . "</p>";
} elseif (is_dir($targetStorage)) {
    echo "<p>📁 Regular directory (may cause access issues)</p>";
} else {
    echo "<p>❌ Storage link not found</p>";
}

echo "<h3>📊 URL Testing:</h3>";

// Test some common file types that might be uploaded
$testUrls = [];

// Find actual files to test
if (is_dir($storageAppPublic)) {
    $folders = ['monthly-reports', 'transaction-proofs', 'po-transports', 'loss-reports'];

    foreach ($folders as $folder) {
        $folderPath = $storageAppPublic . '/' . $folder;
        if (is_dir($folderPath)) {
            $files = array_diff(scandir($folderPath), ['.', '..']);
            if (!empty($files)) {
                $firstFile = reset($files);
                $testUrls[] = [
                    'folder' => $folder,
                    'file' => $firstFile,
                    'url' => 'https://' . $_SERVER['HTTP_HOST'] . '/storage/' . $folder . '/' . $firstFile
                ];
            }
        }
    }
}

if (!empty($testUrls)) {
    echo "<h4>🧪 Testing Actual Files:</h4>";
    foreach ($testUrls as $test) {
        echo "<p><strong>" . htmlspecialchars($test['folder']) . ":</strong> ";
        echo "<a href='" . htmlspecialchars($test['url']) . "' target='_blank'>" . htmlspecialchars($test['file']) . "</a></p>";
    }
} else {
    echo "<p>⚠️ No files found to test</p>";
}

echo "<h3>⚙️ Server Configuration Check:</h3>";

// Check Apache modules
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    $rewriteEnabled = in_array('mod_rewrite', $modules);
    echo "<p>mod_rewrite: " . ($rewriteEnabled ? "✅ Enabled" : "❌ Disabled") . "</p>";
} else {
    echo "<p>⚠️ Cannot check Apache modules</p>";
}

// Check .htaccess
$htaccessPath = $currentDir . '/.htaccess';
if (file_exists($htaccessPath)) {
    echo "<p>.htaccess: ✅ Found</p>";

    $htaccessContent = file_get_contents($htaccessPath);
    if (strpos($htaccessContent, 'RewriteEngine On') !== false) {
        echo "<p>RewriteEngine: ✅ On</p>";
    } else {
        echo "<p>RewriteEngine: ❌ Not found in .htaccess</p>";
    }
} else {
    echo "<p>.htaccess: ❌ Not found</p>";
}

echo "<hr>";
echo "<h3>🔧 Possible Solutions:</h3>";
echo "<ol>";
echo "<li><a href='fix_storage_symlink.php'>Run Storage Symlink Fix</a></li>";
echo "<li>Check if storage URLs return 404 or actual files</li>";
echo "<li>Verify .htaccess rules for storage access</li>";
echo "<li>Clear Laravel application cache</li>";
echo "</ol>";

echo "</body></html>";
?>
