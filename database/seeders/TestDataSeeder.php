<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\SubProject;
use App\Models\Category;
use App\Models\Material;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeder Vendors
        $vendors = [
            ['name' => 'PT ABC Supplier', 'contact_person' => 'John Doe', 'phone' => '081234567890'],
            ['name' => 'CV XYZ Material', 'contact_person' => 'Jane Smith', 'phone' => '081234567891'],
            ['name' => 'Toko Material Jaya', 'contact_person' => 'Budi Santoso', 'phone' => '081234567892'],
        ];

        foreach ($vendors as $vendor) {
            Vendor::create($vendor);
        }

        // Seeder Projects
        $projects = [
            ['name' => 'Project Alpha', 'code' => 'PROJ-ALPHA'],
            ['name' => 'Project Beta', 'code' => 'PROJ-BETA'],
            ['name' => 'Project Gamma', 'code' => 'PROJ-GAMMA'],
        ];

        foreach ($projects as $projectData) {
            $project = Project::create($projectData);

            // Tambah sub projects untuk setiap project
            $subProjects = [
                ['name' => 'Sub Project A - ' . $project->name, 'project_id' => $project->id],
                ['name' => 'Sub Project B - ' . $project->name, 'project_id' => $project->id],
                ['name' => 'Sub Project C - ' . $project->name, 'project_id' => $project->id],
            ];

            foreach ($subProjects as $subProject) {
                SubProject::create($subProject);
            }
        }

        // Seeder Categories dan Materials
        $categories = [
            [
                'name' => 'Kabel',
                'materials' => [
                    ['name' => 'Kabel Listrik 2.5mm', 'unit' => 'meter'],
                    ['name' => 'Kabel Listrik 4mm', 'unit' => 'meter'],
                    ['name' => 'Kabel UTP Cat6', 'unit' => 'meter'],
                ]
            ],
            [
                'name' => 'Tiang',
                'materials' => [
                    ['name' => 'Tiang Beton 9m', 'unit' => 'batang'],
                    ['name' => 'Tiang Beton 12m', 'unit' => 'batang'],
                    ['name' => 'Tiang Besi Galvanis', 'unit' => 'batang'],
                ]
            ],
            [
                'name' => 'Aksesoris',
                'materials' => [
                    ['name' => 'Isolator Keramik', 'unit' => 'pcs'],
                    ['name' => 'Klem Kabel', 'unit' => 'pcs'],
                    ['name' => 'Ground Rod', 'unit' => 'pcs'],
                    ['name' => 'Terminal Block', 'unit' => 'set'],
                ]
            ],
            [
                'name' => 'Peralatan',
                'materials' => [
                    ['name' => 'Tang Kombinasi', 'unit' => 'pcs'],
                    ['name' => 'Obeng Set', 'unit' => 'set'],
                    ['name' => 'Multimeter Digital', 'unit' => 'pcs'],
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create(['name' => $categoryData['name']]);

            foreach ($categoryData['materials'] as $materialData) {
                Material::create([
                    'category_id' => $category->id,
                    'name' => $materialData['name'],
                    'unit' => $materialData['unit']
                ]);
            }
        }
    }
}
