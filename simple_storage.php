<?php
echo "<!DOCTYPE html><html><head><title>Simple Storage Fix</title></head><body>";
echo "<h2>🔧 Simple Storage Link Creator (Updated)</h2>";

// Berdasarkan struktur index.php: __DIR__.'/../../LOGISTIK-DADS/storage/
$currentDir = __DIR__;  // public_html
$laravelDir = dirname(dirname($currentDir)) . '/LOGISTIK-DADS';  // ../../LOGISTIK-DADS
$storageAppPublic = $laravelDir . '/storage/app/public';
$targetStorage = $currentDir . '/storage';

echo "<p><strong>Current Directory:</strong> $currentDir</p>";
echo "<p><strong>Laravel Directory:</strong> $laravelDir</p>";
echo "<p><strong>Storage App Public:</strong> $storageAppPublic</p>";
echo "<p><strong>Target Storage:</strong> $targetStorage</p>";

// Check if Laravel directory exists
if (!is_dir($laravelDir)) {
    echo "<p>❌ Laravel directory not found: $laravelDir</p>";
    echo "<p>Please check your hosting structure!</p>";
    exit;
}

echo "<p>✅ Laravel directory found</p>";

// Step 1: Create storage/app/public if doesn't exist
if (!is_dir($storageAppPublic)) {
    echo "<p>⚠️ Storage app/public not found. Creating it...</p>";
    if (mkdir($storageAppPublic, 0755, true)) {
        echo "<p>✅ Created storage/app/public directory</p>";
    } else {
        echo "<p>❌ Failed to create storage/app/public directory</p>";
    }
}

// Step 2: Remove existing storage if it's not a symlink
if (is_dir($targetStorage) && !is_link($targetStorage)) {
    echo "<p>⚠️ Removing existing storage directory to create symlink...</p>";
    // Simple removal (be careful in production)
    exec("rm -rf " . escapeshellarg($targetStorage));
}

// Step 3: Create symbolic link
if (!is_link($targetStorage)) {
    if (symlink($storageAppPublic, $targetStorage)) {
        echo "<p>✅ Created symbolic link successfully!</p>";
    } else {
        // Fallback: create directory and setup copy
        echo "<p>⚠️ Symbolic link failed, using directory method...</p>";

        if (mkdir($targetStorage, 0755, true)) {
            echo "<p>✅ Created storage directory</p>";

            // Create a sync script for manual copying
            $syncScript = $currentDir . '/sync_storage.php';
            $syncContent = '<?php
// Sync script to copy files from Laravel storage to public storage
$source = "' . $storageAppPublic . '";
$target = "' . $targetStorage . '";

function copyDirectory($src, $dst) {
    if (!is_dir($src)) return false;
    if (!is_dir($dst)) mkdir($dst, 0755, true);

    $dir = opendir($src);
    while (($file = readdir($dir)) !== false) {
        if ($file != "." && $file != "..") {
            if (is_dir($src . "/" . $file)) {
                copyDirectory($src . "/" . $file, $dst . "/" . $file);
            } else {
                copy($src . "/" . $file, $dst . "/" . $file);
            }
        }
    }
    closedir($dir);
    return true;
}

if (copyDirectory($source, $target)) {
    echo "✅ Files synced successfully!";
} else {
    echo "❌ Sync failed!";
}
?>';

            file_put_contents($syncScript, $syncContent);
            echo "<p>📝 Created sync script: <a href='sync_storage.php'>sync_storage.php</a></p>";
        }
    }
} else {
    echo "<p>✅ Symbolic link already exists</p>";
}

// Step 4: Create required folders
$uploadFolders = ['monthly-reports', 'loss-reports', 'transaction-proofs', 'po-transports'];
echo "<h3>Creating Upload Folders:</h3>";

foreach ($uploadFolders as $folder) {
    $folderPath = $storageAppPublic . '/' . $folder;
    if (!is_dir($folderPath)) {
        if (mkdir($folderPath, 0755, true)) {
            echo "<p>✅ Created in storage: $folder</p>";
        } else {
            echo "<p>❌ Failed to create in storage: $folder</p>";
        }
    } else {
        echo "<p>✅ Exists in storage: $folder</p>";
    }
}

// Step 5: Test file creation
$testFile = $storageAppPublic . '/test.txt';
if (file_put_contents($testFile, 'Test file - ' . date('Y-m-d H:i:s'))) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $testUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/storage/test.txt';
    echo "<h3>✅ Test File Created Successfully!</h3>";
    echo "<p>Test URL: <a href='$testUrl' target='_blank'>$testUrl</a></p>";
} else {
    echo "<h3>❌ Failed to create test file</h3>";
}

// Step 6: Check permissions
echo "<h3>Permission Check:</h3>";
echo "<p>Storage App Public readable: " . (is_readable($storageAppPublic) ? "✅" : "❌") . "</p>";
echo "<p>Storage App Public writable: " . (is_writable($storageAppPublic) ? "✅" : "❌") . "</p>";
echo "<p>Target Storage readable: " . (is_readable($targetStorage) ? "✅" : "❌") . "</p>";

echo "<hr>";
echo "<h3>📋 Next Steps:</h3>";
echo "<ol>";
echo "<li>Test the URL above to see if storage files are accessible</li>";
echo "<li>Upload some files in your Laravel app to test</li>";
echo "<li>If using directory method, run sync_storage.php after uploading new files</li>";
echo "<li>Delete these test files when done: simple_storage.php, test.php, sync_storage.php</li>";
echo "</ol>";

echo "</body></html>";
?>
