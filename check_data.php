<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Project;
use App\Models\SubProject;
use App\Models\Category;
use App\Models\Material;

echo "=== DATA CHECK ===\n";
echo "Projects: " . Project::count() . "\n";
echo "SubProjects: " . SubProject::count() . "\n";
echo "Categories: " . Category::count() . "\n";
echo "Materials: " . Material::count() . "\n";

if (Category::count() > 0) {
    echo "\nFirst 10 Categories:\n";
    foreach (Category::limit(10)->get() as $cat) {
        echo "- {$cat->name} (Materials: " . $cat->materials()->count() . ")\n";
    }
}

if (Material::count() > 0) {
    echo "\nFirst 10 Materials:\n";
    foreach (Material::with('category')->limit(10)->get() as $mat) {
        echo "- {$mat->name} ({$mat->unit}) - Category: {$mat->category->name}\n";
    }
}
echo "==================\n";
