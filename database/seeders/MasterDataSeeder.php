<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\SubProject;
use App\Models\Category;
use App\Models\Material;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data safely
        Material::truncate();
        Category::truncate();
        SubProject::truncate();
        Project::truncate();

        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create complete hierarchical data structure
        $this->createCompleteStructure();
    }

    private function createCompleteStructure(): void
    {
        // Define the complete project structure
        $projectsData = [
            'ZTE' => [
                'code' => 'ZTE001',
                'sub_projects' => [
                    'EMR' => $this->getEMRData(),
                    'LINKNET' => $this->getLINKNETData(),
                    'MORATEL' => $this->getMORATELData(),
                    'STARLITE' => $this->getSTARLITEData(),
                ]
            ],
            'YOFC' => [
                'code' => 'YOFC001',
                'sub_projects' => [
                    'EMR' => $this->getEMRData(),
                    'LINKNET' => $this->getLINKNETData(),
                    'MORATEL' => $this->getMORATELData(),
                    'STARLITE' => $this->getSTARLITEData(),
                ]
            ]
        ];

        // Create the structure
        foreach ($projectsData as $projectName => $projectInfo) {
            // Create main project
            $project = Project::create([
                'name' => $projectName,
                'code' => $projectInfo['code']
            ]);

            // Create sub projects with their categories and materials
            foreach ($projectInfo['sub_projects'] as $subProjectName => $subProjectData) {
                $subProject = SubProject::create([
                    'name' => $subProjectName,
                    'project_id' => $project->id
                ]);

                // Create categories and materials for this sub project
                foreach ($subProjectData as $categoryName => $materials) {
                    // Make category name unique by including project name
                    $uniqueCategoryName = $categoryName . ' - ' . $projectName;

                    $category = Category::create([
                        'name' => $uniqueCategoryName,
                        'sub_project_id' => $subProject->id
                    ]);

                    // Create materials for this category
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
    }

    private function getEMRData(): array
    {
        return [
            'Kabel (EMR)' => [
                ['name' => 'Kabel 12 core', 'unit' => 'meter'],
                ['name' => 'Kabel 24 core', 'unit' => 'meter'],
                ['name' => 'Kabel 48 core', 'unit' => 'meter'],
                ['name' => 'Kabel 144 core', 'unit' => 'meter'],
            ],
            'Tiang (EMR)' => [
                ['name' => 'Tiang 7 meter diameter 4 inch', 'unit' => 'batang'],
                ['name' => 'Tiang 7 meter diameter 3 inch', 'unit' => 'batang'],
                ['name' => 'Tiang 9 meter', 'unit' => 'batang'],
            ],
            'Perangkat & Perlengkapan (EMR)' => [
                ['name' => 'FAT (Fiber Access Terminal)', 'unit' => 'unit'],
                ['name' => 'FDT 48', 'unit' => 'unit'],
                ['name' => 'FDT 72', 'unit' => 'unit'],
                ['name' => 'Closure 24 core', 'unit' => 'unit'],
                ['name' => 'Closure 48 core', 'unit' => 'unit'],
                ['name' => 'Closure 144 core', 'unit' => 'unit'],
                ['name' => 'Splitter 1:8', 'unit' => 'unit'],
            ],
            'Pipa HDPE (EMR)' => [
                ['name' => 'HDPE-36/26', 'unit' => 'meter'],
                ['name' => 'HDPE-40/34', 'unit' => 'meter'],
            ],
            'Aksesoris (EMR)' => [
                ['name' => 'Bulldogrip', 'unit' => 'unit'],
                ['name' => 'Clamp pole', 'unit' => 'unit'],
                ['name' => 'Dead End 25-50', 'unit' => 'unit'],
                ['name' => 'Hanger My Rep', 'unit' => 'unit'],
                ['name' => 'Helical 144', 'unit' => 'unit'],
                ['name' => 'Stainless Belt', 'unit' => 'unit'],
                ['name' => 'Stopping', 'unit' => 'unit'],
                ['name' => 'Suspension Corong', 'unit' => 'unit'],
            ],
        ];
    }

    private function getLINKNETData(): array
    {
        return [
            'Kabel (LINKNET)' => [
                ['name' => 'Kabel 12 Fig', 'unit' => 'meter'],
                ['name' => 'Kabel 12 ADSS', 'unit' => 'meter'],
                ['name' => 'Kabel 24 Fig', 'unit' => 'meter'],
                ['name' => 'Kabel 24 ADSS', 'unit' => 'meter'],
                ['name' => 'Kabel 48', 'unit' => 'meter'],
                ['name' => 'Kabel 48 Feeder', 'unit' => 'meter'],
                ['name' => 'Kabel 96', 'unit' => 'meter'],
                ['name' => 'Kabel 144', 'unit' => 'meter'],
            ],
            'Tiang (LINKNET)' => [
                ['name' => 'Tiang 6 meter', 'unit' => 'batang'],
                ['name' => 'Tiang 7 meter', 'unit' => 'batang'],
                ['name' => 'Tiang 8 meter', 'unit' => 'batang'],
                ['name' => 'Tiang 9 meter', 'unit' => 'batang'],
            ],
            'Perangkat & Perlengkapan (LINKNET)' => [
                ['name' => 'FAT', 'unit' => 'unit'],
                ['name' => 'FDT 48', 'unit' => 'unit'],
                ['name' => 'FDT 96', 'unit' => 'unit'],
                ['name' => 'Messenger Strand', 'unit' => 'meter'],
                ['name' => 'Mess Wire 3.6 mm', 'unit' => 'meter'],
                ['name' => 'Splitter 1:8', 'unit' => 'unit'],
                ['name' => 'Sleeve Protector', 'unit' => 'unit'],
                ['name' => 'Section Frame', 'unit' => 'unit'],
                ['name' => 'Drop Cable Marker', 'unit' => 'unit'],
                ['name' => 'Drop Cable Tie', 'unit' => 'unit'],
                ['name' => 'Closure In Line ILC 24 core', 'unit' => 'unit'],
                ['name' => 'Closure In Line ILC 144 core', 'unit' => 'unit'],
                ['name' => 'Splice In Line Clos 3M 48 core', 'unit' => 'unit'],
                ['name' => 'Splice In Line Clos 3M 96 core', 'unit' => 'unit'],
                ['name' => 'Support Hook', 'unit' => 'unit'],
                ['name' => 'JC 48', 'unit' => 'unit'],
            ],
            'Aksesoris (LINKNET)' => [
                ['name' => 'Bracket J Type', 'unit' => 'unit'],
                ['name' => 'Clamp Round 2 inch', 'unit' => 'unit'],
                ['name' => 'Clamp Round 4 inch', 'unit' => 'unit'],
                ['name' => 'Clamp Round 6 inch', 'unit' => 'unit'],
                ['name' => 'Clamp Baut + Mur 10 cm', 'unit' => 'set'],
                ['name' => 'Buldog Grip', 'unit' => 'unit'],
                ['name' => 'Drop Pre-Forms', 'unit' => 'unit'],
                ['name' => 'Acrylic Tag for LN', 'unit' => 'unit'],
                ['name' => 'Strap 10" Cable Support 400 mm', 'unit' => 'unit'],
                ['name' => 'Strap 16" Cable Support 400 mm', 'unit' => 'unit'],
                ['name' => 'Lashing Wire (1 Roll = 366 Meter)', 'unit' => 'roll'],
                ['name' => 'Strand Clamp 50-70', 'unit' => 'unit'],
                ['name' => 'Strand Clamp 144 Core', 'unit' => 'unit'],
                ['name' => 'Helical Tension', 'unit' => 'unit'],
            ],
            'Pipa (LINKNET)' => [
                ['name' => 'Pipa HDPE 50x44 mm (tebal 3 mm)', 'unit' => 'meter'],
                ['name' => 'Pipa Flexible 3/4 inch', 'unit' => 'meter'],
                ['name' => 'Pipa Galvanis 3/4 inch', 'unit' => 'meter'],
            ],
        ];
    }

    private function getMORATELData(): array
    {
        return [
            'Kabel (MORATEL)' => [
                ['name' => 'Kabel 12 core', 'unit' => 'meter'],
                ['name' => 'Kabel 24 core', 'unit' => 'meter'],
                ['name' => 'Kabel 96 core', 'unit' => 'meter'],
                ['name' => 'Kabel 144 core', 'unit' => 'meter'],
            ],
            'Tiang (MORATEL)' => [
                ['name' => 'Tiang 6 meter', 'unit' => 'batang'],
                ['name' => 'Tiang 7 meter', 'unit' => 'batang'],
                ['name' => 'Tiang 9 meter', 'unit' => 'batang'],
            ],
            'Perangkat & Perlengkapan (MORATEL)' => [
                ['name' => 'ODB 16', 'unit' => 'unit'],
                ['name' => 'OCC 144', 'unit' => 'unit'],
                ['name' => 'Closure 24 core', 'unit' => 'unit'],
                ['name' => 'Closure 96 core', 'unit' => 'unit'],
                ['name' => 'Patchcord', 'unit' => 'unit'],
                ['name' => 'HDPE 34/40', 'unit' => 'meter'],
            ],
            'Aksesoris (MORATEL)' => [
                ['name' => 'Bulldogrip', 'unit' => 'unit'],
                ['name' => 'Clamp pole', 'unit' => 'unit'],
                ['name' => 'Dead End 25-50', 'unit' => 'unit'],
                ['name' => 'Dead End 50-70', 'unit' => 'unit'],
                ['name' => 'Slack Hanger', 'unit' => 'unit'],
                ['name' => 'Helical 144', 'unit' => 'unit'],
                ['name' => 'Stainless Belt', 'unit' => 'unit'],
                ['name' => 'Stopping', 'unit' => 'unit'],
                ['name' => 'Suspension Corong', 'unit' => 'unit'],
            ],
        ];
    }

    private function getSTARLITEData(): array
    {
        return [
            'Kabel ADSS (STARLITE)' => [
                ['name' => 'Kabel 24 core', 'unit' => 'meter'],
                ['name' => 'Kabel 98 core', 'unit' => 'meter'],
                ['name' => 'Kabel 96 core', 'unit' => 'meter'],
                ['name' => 'Kabel 149 core', 'unit' => 'meter'],
            ],
            'Kabel Precon (STARLITE)' => [
                ['name' => 'Kabel Precon 50', 'unit' => 'meter'],
                ['name' => 'Kabel Precon 100', 'unit' => 'meter'],
                ['name' => 'Kabel Precon 150', 'unit' => 'meter'],
            ],
            'Tiang (STARLITE)' => [
                ['name' => 'Tiang 6 meter', 'unit' => 'batang'],
                ['name' => 'Tiang 7 meter', 'unit' => 'batang'],
                ['name' => 'Tiang 7 m3', 'unit' => 'batang'],
                ['name' => 'Tiang 7 m4', 'unit' => 'batang'],
                ['name' => 'Tiang 8 meter', 'unit' => 'batang'],
                ['name' => 'Tiang 9 meter', 'unit' => 'batang'],
            ],
            'Material Electrical (STARLITE)' => [
                ['name' => 'Material Electrical (TBD)', 'unit' => 'unit'],
            ],
            'Material Aksesoris (STARLITE)' => [
                ['name' => 'Material Aksesoris (TBD)', 'unit' => 'unit'],
            ],
        ];
    }
}
