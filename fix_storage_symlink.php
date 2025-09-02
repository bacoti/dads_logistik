<?php
echo "<!DOCTYPE html><html><head><title>Fix Storage Symlink</title></head><body>";
echo "<h2>🔧 Fix Storage Symbolic Link</h2>";

$currentDir = __DIR__;
$laravelDir = dirname(dirname($currentDir)) . '/LOGISTIK-DADS';
$storageAppPublic = $laravelDir . '/storage/app/public';
$targetStorage = $currentDir . '/storage';

echo "<h3>Current Situation Analysis:</h3>";
echo "<p>Current Directory: " . $currentDir . "</p>";
echo "<p>Laravel Directory: " . $laravelDir . "</p>";
echo "<p>Storage Source: " . $storageAppPublic . "</p>";
echo "<p>Target Storage: " . $targetStorage . "</p>";

// Check current status
if (is_link($targetStorage)) {
    echo "<p>📎 Current: Symbolic link → " . readlink($targetStorage) . "</p>";
} elseif (is_dir($targetStorage)) {
    echo "<p>📁 Current: Regular directory (this might be the problem!)</p>";
} else {
    echo "<p>❌ Current: Not found</p>";
}

echo "<h3>🔧 Fixing Storage Link:</h3>";

// Step 1: Backup existing files if it's a directory
if (is_dir($targetStorage) && !is_link($targetStorage)) {
    $backupDir = $currentDir . '/storage_backup_' . date('YmdHis');
    echo "<p>📦 Backing up existing storage directory...</p>";

    if (rename($targetStorage, $backupDir)) {
        echo "<p>✅ Backup created: " . basename($backupDir) . "</p>";
    } else {
        echo "<p>❌ Failed to create backup</p>";
        exit;
    }
}

// Step 2: Create symbolic link
echo "<p>🔗 Creating symbolic link...</p>";
if (symlink($storageAppPublic, $targetStorage)) {
    echo "<p>✅ Symbolic link created successfully!</p>";
} else {
    echo "<p>❌ Failed to create symbolic link</p>";

    // Restore backup if symlink failed
    if (isset($backupDir) && is_dir($backupDir)) {
        rename($backupDir, $targetStorage);
        echo "<p>🔄 Restored backup directory</p>";
    }
    exit;
}

// Step 3: Verify the link
echo "<h3>✅ Verification:</h3>";
if (is_link($targetStorage)) {
    $linkTarget = readlink($targetStorage);
    echo "<p>✅ Symbolic link verified: " . $linkTarget . "</p>";

    if ($linkTarget === $storageAppPublic) {
        echo "<p>✅ Link points to correct location</p>";
    } else {
        echo "<p>⚠️ Link points to unexpected location</p>";
    }
} else {
    echo "<p>❌ Symbolic link verification failed</p>";
}

// Step 4: Test file access
echo "<h3>🧪 Testing File Access:</h3>";
$testFile = $storageAppPublic . '/test-symlink.txt';
$testContent = 'Symlink test - ' . date('Y-m-d H:i:s');

if (file_put_contents($testFile, $testContent)) {
    echo "<p>✅ Test file created in storage</p>";

    $publicTestFile = $targetStorage . '/test-symlink.txt';
    if (file_exists($publicTestFile) && file_get_contents($publicTestFile) === $testContent) {
        echo "<p>✅ File accessible through symlink</p>";

        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $testUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/storage/test-symlink.txt';
        echo "<p>🌐 Test URL: <a href='" . $testUrl . "' target='_blank'>" . $testUrl . "</a></p>";

    } else {
        echo "<p>❌ File not accessible through symlink</p>";
    }
} else {
    echo "<p>❌ Cannot create test file</p>";
}

// Step 5: Check missing loss-reports folder
$lossReportsDir = $storageAppPublic . '/loss-reports';
if (!is_dir($lossReportsDir)) {
    echo "<h3>📁 Creating Missing Folders:</h3>";
    if (mkdir($lossReportsDir, 0755, true)) {
        echo "<p>✅ Created loss-reports folder</p>";
    } else {
        echo "<p>❌ Failed to create loss-reports folder</p>";
    }
}

echo "<hr>";
echo "<h3>🎯 Final Instructions:</h3>";
echo "<ol>";
echo "<li>Clear Laravel cache: <code>php artisan config:clear && php artisan route:clear && php artisan view:clear</code></li>";
echo "<li>Test upload a new file in your Laravel application</li>";
echo "<li>Check if old uploaded files are still accessible</li>";
echo "<li>If files are missing, check the backup directory created earlier</li>";
echo "</ol>";

echo "<p><strong>Note:</strong> If you had files in the old storage directory, they are backed up. You may need to copy them back to the Laravel storage location.</p>";

echo "</body></html>";
?>
