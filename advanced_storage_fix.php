<?php
echo "<!DOCTYPE html><html><head><title>Advanced Storage Fix</title></head><body>";
echo "<h2>🛠️ Advanced Storage Fix</h2>";

$currentDir = __DIR__;
$laravelDir = dirname(dirname($currentDir)) . '/LOGISTIK-DADS';
$storageAppPublic = $laravelDir . '/storage/app/public';
$targetStorage = $currentDir . '/storage';

echo "<h3>📋 Current Analysis:</h3>";
echo "<p>Current Dir: " . $currentDir . "</p>";
echo "<p>Storage Source: " . $storageAppPublic . "</p>";
echo "<p>Target Storage: " . $targetStorage . "</p>";

// Check what exists
echo "<h3>🔍 Current Status:</h3>";
if (is_link($targetStorage)) {
    echo "<p>🔗 Symbolic link exists → " . readlink($targetStorage) . "</p>";
} elseif (is_dir($targetStorage)) {
    $fileCount = count(array_diff(scandir($targetStorage), ['.', '..']));
    echo "<p>📁 Regular directory exists with " . $fileCount . " items</p>";

    // List what's in the current storage directory
    echo "<h4>Contents of current storage directory:</h4>";
    $items = array_diff(scandir($targetStorage), ['.', '..']);
    foreach ($items as $item) {
        $itemPath = $targetStorage . '/' . $item;
        if (is_dir($itemPath)) {
            $subCount = count(array_diff(scandir($itemPath), ['.', '..']));
            echo "<p>📁 " . $item . "/ (" . $subCount . " items)</p>";
        } else {
            $size = filesize($itemPath);
            echo "<p>📄 " . $item . " (" . round($size/1024, 2) . " KB)</p>";
        }
    }
} else {
    echo "<p>❌ Target storage not found</p>";
}

echo "<hr>";
echo "<h3>🔧 Method 1: Force Remove and Create Symlink</h3>";

// Method 1: Aggressive approach
if (isset($_GET['method1']) && $_GET['method1'] === 'execute') {
    echo "<p>🚨 <strong>Executing Method 1...</strong></p>";

    // Step 1: Force remove existing storage
    if (file_exists($targetStorage)) {
        echo "<p>🗑️ Removing existing storage...</p>";
        $result = shell_exec("rm -rf " . escapeshellarg($targetStorage) . " 2>&1");
        if ($result) {
            echo "<p>⚠️ Output: " . htmlspecialchars($result) . "</p>";
        }

        if (!file_exists($targetStorage)) {
            echo "<p>✅ Old storage removed</p>";
        } else {
            echo "<p>❌ Failed to remove old storage</p>";
            goto method2;
        }
    }

    // Step 2: Create symlink
    echo "<p>🔗 Creating symbolic link...</p>";
    $symlinkResult = symlink($storageAppPublic, $targetStorage);

    if ($symlinkResult) {
        echo "<p>✅ Symbolic link created successfully!</p>";

        // Test the symlink
        if (is_link($targetStorage)) {
            echo "<p>✅ Symlink verified: " . readlink($targetStorage) . "</p>";
        }

        goto test_access;
    } else {
        echo "<p>❌ Symlink creation failed</p>";
        echo "<p>Error: " . error_get_last()['message'] . "</p>";
    }
} else {
    echo "<p><a href='?method1=execute' style='background: red; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>⚠️ EXECUTE METHOD 1 (DESTRUCTIVE)</a></p>";
    echo "<p><small>This will DELETE the current storage directory and create a symbolic link</small></p>";
}

method2:
echo "<hr>";
echo "<h3>🔧 Method 2: Manual Copy and Create Script</h3>";

if (isset($_GET['method2']) && $_GET['method2'] === 'execute') {
    echo "<p>🔄 <strong>Executing Method 2...</strong></p>";

    // Create a sync script
    $syncScript = $currentDir . '/sync_storage.php';
    $syncContent = '<?php
echo "<h2>Storage Sync Script</h2>";

$source = "' . $storageAppPublic . '";
$target = "' . $targetStorage . '";

function syncDirectory($src, $dst) {
    if (!is_dir($src)) {
        echo "<p>❌ Source not found: " . htmlspecialchars($src) . "</p>";
        return false;
    }

    if (!is_dir($dst)) {
        mkdir($dst, 0755, true);
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($src, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {
        $targetPath = $dst . "/" . $iterator->getSubPathName();

        if ($item->isDir()) {
            if (!is_dir($targetPath)) {
                mkdir($targetPath, 0755, true);
                echo "<p>📁 Created: " . htmlspecialchars($iterator->getSubPathName()) . "</p>";
            }
        } else {
            if (!file_exists($targetPath) || filemtime($item) > filemtime($targetPath)) {
                copy($item, $targetPath);
                echo "<p>📄 Copied: " . htmlspecialchars($iterator->getSubPathName()) . "</p>";
            }
        }
    }

    return true;
}

if (syncDirectory($source, $target)) {
    echo "<p>✅ Sync completed!</p>";
} else {
    echo "<p>❌ Sync failed!</p>";
}

echo "<p><a href=\\"../\\">Back to main site</a></p>";
?>';

    if (file_put_contents($syncScript, $syncContent)) {
        echo "<p>✅ Sync script created: <a href='sync_storage.php'>sync_storage.php</a></p>";
        echo "<p>This script will keep your storage directory synchronized with Laravel storage</p>";
    } else {
        echo "<p>❌ Failed to create sync script</p>";
    }

} else {
    echo "<p><a href='?method2=execute' style='background: blue; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>🔄 EXECUTE METHOD 2 (SAFE)</a></p>";
    echo "<p><small>This will create a sync script to keep directories synchronized</small></p>";
}

echo "<hr>";
echo "<h3>🔧 Method 3: Update Laravel Config</h3>";

// Method 3: Change Laravel configuration to use public disk differently
if (isset($_GET['method3']) && $_GET['method3'] === 'execute') {
    echo "<p>⚙️ <strong>Laravel Configuration Update...</strong></p>";

    $configScript = $currentDir . '/update_storage_config.php';
    $configContent = '<?php
echo "<h2>Laravel Storage Config Update</h2>";

// This script helps update your Laravel storage configuration
// to work better with directory-based storage instead of symlinks

echo "<h3>Instructions:</h3>";
echo "<ol>";
echo "<li>In your Laravel .env file, ensure: FILESYSTEM_DISK=public</li>";
echo "<li>In your controllers, use: Storage::disk(\"public\")->url() for URLs</li>";
echo "<li>In your models, create accessor methods like this:</li>";
echo "</ol>";

echo "<h4>Example Model Accessor:</h4>";
echo "<pre style=\"background: #f5f5f5; padding: 10px;\">";
echo htmlspecialchars("
// In your model (e.g., MonthlyReport.php)
public function getFileUrlAttribute()
{
    if (\$this->file_path) {
        return asset(\"storage/\" . \$this->file_path);
    }
    return null;
}

// Usage in blade: {{ \$report->file_url }}
");
echo "</pre>";

echo "<h4>Example Controller Method:</h4>";
echo "<pre style=\"background: #f5f5f5; padding: 10px;\">";
echo htmlspecialchars("
// In your controller
public function download(\$id)
{
    \$report = MonthlyReport::findOrFail(\$id);

    \$filePath = storage_path(\"app/public/\" . \$report->file_path);

    if (file_exists(\$filePath)) {
        return response()->download(\$filePath);
    }

    abort(404);
}
");
echo "</pre>";

echo "<p><a href=\"../\">Back to main site</a></p>";
?>';

    if (file_put_contents($configScript, $configContent)) {
        echo "<p>✅ Config helper created: <a href='update_storage_config.php'>update_storage_config.php</a></p>";
    }
} else {
    echo "<p><a href='?method3=execute' style='background: green; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>📝 CREATE CONFIG HELPER</a></p>";
    echo "<p><small>This will create a guide for updating Laravel configuration</small></p>";
}

test_access:
if (isset($symlinkResult) && $symlinkResult) {
    echo "<hr>";
    echo "<h3>🧪 Testing File Access:</h3>";

    // Test if files are accessible
    $testFiles = [
        'monthly-reports/monthly_report_4_October_1756730998.xlsx',
        'transaction-proofs/5ciQF2hpKJv6CWT3bIdsqOxm0ABki7ATLLnRxWJ4.jpg'
    ];

    foreach ($testFiles as $testFile) {
        $url = 'https://' . $_SERVER['HTTP_HOST'] . '/storage/' . $testFile;
        echo "<p>🔗 <a href='" . $url . "' target='_blank'>" . $testFile . "</a></p>";
    }
}

echo "<hr>";
echo "<h3>📋 Manual Instructions:</h3>";
echo "<p>If all automated methods fail, you can manually run this command via SSH or terminal:</p>";
echo "<code style='background: #f0f0f0; padding: 5px;'>ln -sfn " . $storageAppPublic . " " . $targetStorage . "</code>";

echo "</body></html>";
?>
