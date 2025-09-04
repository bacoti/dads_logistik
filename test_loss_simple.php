<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\LossReport;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing LossReport model...\n";

    // Test basic query
    $count = LossReport::count();
    echo "Total loss reports: $count\n";

    if ($count > 0) {
        $first = LossReport::first();
        echo "First report ID: " . $first->id . "\n";
        echo "First report date: " . ($first->loss_date ?? 'null') . "\n";
        echo "User relation exists: " . ($first->user ? 'yes' : 'no') . "\n";
        echo "Project relation exists: " . ($first->project ? 'yes' : 'no') . "\n";
    }

    echo "LossReport model works fine!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
