<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\SubProject;
use App\Models\Category;
use App\Models\Material;

class TestMasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create test vendors
        $vendors = [
            'PT Sinar Jaya',
            'CV Mitra Bangunan',
            'UD Karya Mandiri'
        ];

        foreach ($vendors as $vendorName) {
            Vendor::create(['name' => $vendorName]);
        }

        // Create test projects
        $projects = [
            ['name' => 'Pembangunan Gedung Kantor', 'code' => 'PGK001'],
            ['name' => 'Renovasi Pabrik', 'code' => 'RPB002'],
        ];

        foreach ($projects as $projectData) {
            $project = Project::create($projectData);

            // Create sub projects for each project
            $subProjectNames = [
                'Fase 1 - Persiapan',
                'Fase 2 - Pembangunan Struktur',
                'Fase 3 - Finishing'
            ];

            foreach ($subProjectNames as $subProjectName) {
                $subProject = SubProject::create([
                    'name' => $subProjectName,
                    'project_id' => $project->id
                ]);

                // Create categories for each sub project
                $categoryNames = [
                    'Bahan Bangunan',
                    'Alat Kerja',
                    'Material Finishing'
                ];

                foreach ($categoryNames as $categoryName) {
                    $category = Category::create([
                        'name' => $categoryName,
                        'sub_project_id' => $subProject->id
                    ]);

                    // Create materials for each category
                    $materials = [
                        ['name' => 'Semen Portland', 'unit' => 'sak'],
                        ['name' => 'Besi Beton 10mm', 'unit' => 'batang'],
                        ['name' => 'Pasir Halus', 'unit' => 'm3']
                    ];

                    foreach ($materials as $materialData) {
                        Material::create([
                            'name' => $materialData['name'],
                            'unit' => $materialData['unit'],
                            'category_id' => $category->id,
                            'sub_project_id' => $subProject->id
                        ]);
                    }
                }
            }
        }

        echo "Test master data created successfully!\n";
        echo "- " . Vendor::count() . " vendors\n";
        echo "- " . Project::count() . " projects\n";
        echo "- " . SubProject::count() . " sub projects\n";
        echo "- " . Category::count() . " categories\n";
        echo "- " . Material::count() . " materials\n";
    }
}
