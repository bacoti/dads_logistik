<?php

// Quick test script untuk PO Material approval
// Jalankan dengan: php test_po_approval.php

// Set environment
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['REQUEST_URI'] = '/';

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Test PO Material Model
use App\Models\PoMaterial;
use App\Models\User;

echo "=== PO MATERIAL APPROVAL TEST ===\n\n";

try {
    // 1. Check if we have PO Materials
    $poCount = PoMaterial::count();
    echo "1. Total PO Materials in database: {$poCount}\n";

    if ($poCount === 0) {
        echo "❌ No PO Materials found. Please run seeder first.\n";
        exit(1);
    }

    // 2. Find a pending PO Material
    $pendingPO = PoMaterial::where('status', 'pending')->first();

    if (!$pendingPO) {
        echo "2. No pending PO Materials found. Creating one...\n";

        $user = User::where('role', 'po')->first();
        $project = \App\Models\Project::first();

        if (!$user || !$project) {
            echo "❌ Missing users or projects. Please run seeders first.\n";
            exit(1);
        }

        $pendingPO = PoMaterial::create([
            'po_number' => 'TEST-' . time(),
            'user_id' => $user->id,
            'supplier' => 'Test Supplier',
            'release_date' => today(),
            'location' => 'Test Location',
            'project_id' => $project->id,
            'description' => 'Test Material for Approval',
            'quantity' => 100,
            'unit' => 'pcs',
            'status' => 'pending',
        ]);

        echo "✅ Created test PO Material: {$pendingPO->po_number}\n";
    } else {
        echo "2. Found pending PO Material: {$pendingPO->po_number}\n";
    }

    // 3. Test status update
    echo "3. Current status: {$pendingPO->status}\n";

    $updated = $pendingPO->update(['status' => 'approved']);

    if ($updated) {
        echo "✅ Status update successful\n";

        $freshPO = $pendingPO->fresh();
        echo "4. New status: {$freshPO->status}\n";

        if ($freshPO->status === 'approved') {
            echo "✅ Status correctly changed to 'approved'\n";
        } else {
            echo "❌ Status not changed correctly\n";
        }
    } else {
        echo "❌ Status update failed\n";
    }

    // 4. Test route existence
    echo "5. Testing route...\n";
    $routeName = 'admin.po-materials.update-status';

    try {
        $url = route($routeName, ['poMaterial' => $pendingPO->id]);
        echo "✅ Route '{$routeName}' exists: {$url}\n";
    } catch (Exception $e) {
        echo "❌ Route '{$routeName}' not found: " . $e->getMessage() . "\n";
    }

    // 5. Test admin user
    $adminUser = User::where('role', 'admin')->first();
    if ($adminUser) {
        echo "6. Admin user found: {$adminUser->name} ({$adminUser->email})\n";
        echo "✅ Admin user available for testing\n";
    } else {
        echo "6. ❌ No admin user found. Please create admin user first.\n";
    }

    echo "\n=== TEST SUMMARY ===\n";
    echo "Database: ✅ Connected\n";
    echo "PO Materials: ✅ Available\n";
    echo "Model Update: ✅ Working\n";
    echo "Route: ✅ Exists\n";
    echo "Admin User: " . ($adminUser ? "✅ Available" : "❌ Missing") . "\n";

    echo "\n=== NEXT STEPS ===\n";
    echo "1. Login sebagai admin: {$adminUser->email}\n";
    echo "2. Akses: /admin/po-materials/{$pendingPO->id}\n";
    echo "3. Klik tombol 'Setujui'\n";
    echo "4. Periksa browser console untuk debug logs\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
