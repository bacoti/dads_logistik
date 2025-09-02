<?php
echo "<!DOCTYPE html><html><head><title>Manual Storage Fix</title></head><body>";
echo "<h2>🔧 Manual Storage Fix</h2>";

$currentDir = __DIR__;
$laravelStorage = '/home/u203849739/domains/ptdads.co.id/LOGISTIK-DADS/storage/app/public';
$targetStorage = $currentDir . '/storage';

echo "<h3>📋 Paths:</h3>";
echo "<p><strong>Source:</strong> $laravelStorage</p>";
echo "<p><strong>Target:</strong> $targetStorage</p>";

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'remove_old') {
        echo "<h3>🗑️ Removing Old Storage...</h3>";
        if (is_dir($targetStorage)) {
            $result = exec("rm -rf " . escapeshellarg($targetStorage) . " 2>&1", $output, $return_var);
            if ($return_var === 0) {
                echo "<p>✅ Old storage removed successfully</p>";
            } else {
                echo "<p>❌ Failed to remove old storage: " . implode(", ", $output) . "</p>";
            }
        } else {
            echo "<p>⚠️ No old storage found</p>";
        }
    }

    if ($action === 'create_symlink') {
        echo "<h3>🔗 Creating Symbolic Link...</h3>";
        if (symlink($laravelStorage, $targetStorage)) {
            echo "<p>✅ Symbolic link created successfully!</p>";

            // Test the link
            if (is_link($targetStorage)) {
                echo "<p>✅ Link verified: " . readlink($targetStorage) . "</p>";
            }
        } else {
            echo "<p>❌ Failed to create symbolic link</p>";
            $error = error_get_last();
            if ($error) {
                echo "<p>Error: " . $error['message'] . "</p>";
            }
        }
    }

    if ($action === 'copy_manual') {
        echo "<h3>📁 Manual Copy Method...</h3>";

        if (!is_dir($targetStorage)) {
            mkdir($targetStorage, 0755, true);
        }

        $folders = ['monthly-reports', 'transaction-proofs', 'po-transports', 'documents', 'loss-reports'];

        foreach ($folders as $folder) {
            $sourcePath = $laravelStorage . '/' . $folder;
            $targetPath = $targetStorage . '/' . $folder;

            if (is_dir($sourcePath)) {
                echo "<p>📁 Processing: $folder</p>";

                // Create target folder
                if (!is_dir($targetPath)) {
                    mkdir($targetPath, 0755, true);
                }

                // Copy files
                $files = glob($sourcePath . '/*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        $filename = basename($file);
                        $targetFile = $targetPath . '/' . $filename;

                        if (copy($file, $targetFile)) {
                            echo "<p>✅ Copied: $filename</p>";
                        } else {
                            echo "<p>❌ Failed: $filename</p>";
                        }
                    }
                }
            }
        }

        echo "<p>🎉 Manual copy completed!</p>";
    }
}

// Check current status
echo "<h3>📊 Current Status:</h3>";
if (is_link($targetStorage)) {
    echo "<p>✅ Symbolic link exists: " . readlink($targetStorage) . "</p>";
} elseif (is_dir($targetStorage)) {
    $count = count(array_diff(scandir($targetStorage), ['.', '..']));
    echo "<p>📁 Directory exists with $count items</p>";
} else {
    echo "<p>❌ Storage not found</p>";
}

echo "<h3>🎯 Actions:</h3>";
?>

<form method="post" style="margin: 10px 0;">
    <button type="submit" name="action" value="remove_old"
            style="background: red; color: white; padding: 10px; border: none; border-radius: 5px; margin: 5px;"
            onclick="return confirm('This will delete the current storage directory. Are you sure?')">
        🗑️ Remove Old Storage
    </button>
</form>

<form method="post" style="margin: 10px 0;">
    <button type="submit" name="action" value="create_symlink"
            style="background: blue; color: white; padding: 10px; border: none; border-radius: 5px; margin: 5px;">
        🔗 Create Symbolic Link
    </button>
</form>

<form method="post" style="margin: 10px 0;">
    <button type="submit" name="action" value="copy_manual"
            style="background: green; color: white; padding: 10px; border: none; border-radius: 5px; margin: 5px;">
        📁 Manual Copy Files
    </button>
</form>

<?php
echo "<h3>🧪 Test Files:</h3>";

// Show test links if storage exists
if (is_dir($targetStorage) || is_link($targetStorage)) {
    $testFiles = [];

    if (is_dir($laravelStorage . '/transaction-proofs')) {
        $files = glob($laravelStorage . '/transaction-proofs/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        if (!empty($files)) {
            $testFiles[] = 'transaction-proofs/' . basename($files[0]);
        }
    }

    if (is_dir($laravelStorage . '/monthly-reports')) {
        $files = glob($laravelStorage . '/monthly-reports/*.{xlsx,xls,pdf}', GLOB_BRACE);
        if (!empty($files)) {
            $testFiles[] = 'monthly-reports/' . basename($files[0]);
        }
    }

    foreach ($testFiles as $testFile) {
        $url = 'https://' . $_SERVER['HTTP_HOST'] . '/storage/' . $testFile;
        echo "<p>🔗 <a href='$url' target='_blank'>$testFile</a></p>";
    }
}

echo "<hr>";
echo "<p><strong>Instructions:</strong></p>";
echo "<ol>";
echo "<li>Click 'Remove Old Storage' first (if storage directory exists)</li>";
echo "<li>Then click 'Create Symbolic Link'</li>";
echo "<li>If symbolic link fails, use 'Manual Copy Files'</li>";
echo "<li>Test the links above to see if files are accessible</li>";
echo "</ol>";

echo "</body></html>";
?>
