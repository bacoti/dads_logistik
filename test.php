<?php
echo "<!DOCTYPE html><html><head><title>Laravel Hosting Test</title></head><body>";
echo "<h2>✅ PHP is working!</h2>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p><strong>Current Directory:</strong> " . __DIR__ . "</p>";
echo "<p><strong>Server Name:</strong> " . $_SERVER['SERVER_NAME'] . "</p>";
echo "<p><strong>Script Name:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";

// Based on your index.php structure
$laravelDir = dirname(dirname(__DIR__)) . '/LOGISTIK-DADS';
echo "<p><strong>Laravel Directory (calculated):</strong> " . $laravelDir . "</p>";

// Check if Laravel files exist
$laravelFiles = [
    'artisan' => $laravelDir . '/artisan',
    'composer.json' => $laravelDir . '/composer.json',
    '.env' => $laravelDir . '/.env',
    'storage' => $laravelDir . '/storage',
    'vendor' => $laravelDir . '/vendor'
];

echo "<h3>Laravel Files Check:</h3>";
foreach ($laravelFiles as $name => $path) {
    $status = file_exists($path) ? "✅ Found" : "❌ Not Found";
    if ($name === 'storage' && is_dir($path)) {
        $status .= " (Directory)";
    }
    echo "<p>$name: $status</p>";
}

// Check storage structure
echo "<h3>Storage Structure:</h3>";
$storagePaths = [
    'Laravel Storage' => $laravelDir . '/storage',
    'Storage App' => $laravelDir . '/storage/app',
    'Storage App Public' => $laravelDir . '/storage/app/public',
    'Public Storage (target)' => __DIR__ . '/storage'
];

foreach ($storagePaths as $name => $path) {
    if (is_dir($path)) {
        $fileCount = count(array_diff(scandir($path), ['.', '..']));
        echo "<p>$name: ✅ Directory ($fileCount items)</p>";
    } elseif (is_link($path)) {
        echo "<p>$name: 🔗 Symbolic Link → " . readlink($path) . "</p>";
    } elseif (file_exists($path)) {
        echo "<p>$name: 📄 File</p>";
    } else {
        echo "<p>$name: ❌ Not Found</p>";
    }
}

// Check .env content (basic)
$envFile = $laravelDir . '/.env';
if (file_exists($envFile)) {
    echo "<h3>.ENV Configuration:</h3>";
    $envContent = file_get_contents($envFile);

    // Extract key values
    $envVars = ['APP_URL', 'FILESYSTEM_DISK', 'APP_ENV'];
    foreach ($envVars as $var) {
        if (preg_match("/^$var=(.*)$/m", $envContent, $matches)) {
            echo "<p>$var: " . trim($matches[1]) . "</p>";
        } else {
            echo "<p>$var: ❌ Not Set</p>";
        }
    }
}

echo "<hr>";
echo "<h3>🔧 Next Steps:</h3>";
echo "<ol>";
echo "<li>If Laravel files are found, run <a href='simple_storage.php'>simple_storage.php</a></li>";
echo "<li>Check if storage symbolic link is created properly</li>";
echo "<li>Test file upload in your Laravel application</li>";
echo "</ol>";

echo "</body></html>";
?>
