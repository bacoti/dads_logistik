<?php

namespace Database\Seeders;

use App\Models\Vendor;
use App\Models\Project;
use App\Models\SubProject;
use App\Models\Category;
use App\Models\Material;
use Illuminate\Database\Seeder;

class ComprehensiveMasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed 15 Vendors
        $vendors = [
            ['name' => 'PT. Bina Karya Mandiri'],
            ['name' => 'CV. Sumber Makmur Jaya'],
            ['name' => 'Toko Material Prima'],
            ['name' => 'UD. Harapan Baru Sejahtera'],
            ['name' => 'PT. Multi Sarana Teknik'],
            ['name' => 'CV. Aneka Bangunan'],
            ['name' => 'PT. Graha Material Indonesia'],
            ['name' => 'UD. Maju Bersama'],
            ['name' => 'CV. Bangun Jaya Utama'],
            ['name' => 'PT. Sukses Mandiri Group'],
            ['name' => 'Toko Besi & Baja Sejahtera'],
            ['name' => 'CV. Berkah Material'],
            ['name' => 'PT. Indah Karya Bangunan'],
            ['name' => 'UD. Barokah Konstruksi'],
            ['name' => 'CV. Mitra Pembangunan']
        ];

        foreach ($vendors as $vendor) {
            Vendor::firstOrCreate($vendor);
        }

        // Seed 10 Main Projects
        $projects = [
            ['name' => 'Pembangunan Jalan Tol Surabaya-Malang', 'code' => 'JSM001'],
            ['name' => 'Renovasi Gedung Perkantoran Pusat', 'code' => 'RGP002'],
            ['name' => 'Proyek Infrastruktur IT Nasional', 'code' => 'IIT003'],
            ['name' => 'Pembangunan Mall Metropolitan', 'code' => 'PMM004'],
            ['name' => 'Konstruksi Jembatan Sungai Brantas', 'code' => 'JSB005'],
            ['name' => 'Proyek Perumahan Green Valley', 'code' => 'PGV006'],
            ['name' => 'Pembangunan Bandara Regional', 'code' => 'PBR007'],
            ['name' => 'Konstruksi Terminal Bus Terpadu', 'code' => 'TBT008'],
            ['name' => 'Proyek Gedung Rumah Sakit Umum', 'code' => 'RSU009'],
            ['name' => 'Pembangunan Stadion Olahraga', 'code' => 'PSO010']
        ];

        $createdProjects = [];
        foreach ($projects as $projectData) {
            $project = Project::firstOrCreate([
                'name' => $projectData['name']
            ], [
                'code' => $projectData['code']
            ]);
            $createdProjects[] = $project;
        }

        // Seed 10 Sub Projects (1 per main project)
        $subProjectNames = [
            'Fase Persiapan Lahan',
            'Fase Konstruksi Utama',
            'Fase Finishing',
            'Fase Instalasi Sistem',
            'Fase Testing & Commissioning',
            'Fase Interior Design',
            'Fase Landscaping',
            'Fase Pemasangan Utilitas',
            'Fase Quality Control',
            'Fase Handover'
        ];

        foreach ($createdProjects as $index => $project) {
            SubProject::firstOrCreate([
                'name' => $subProjectNames[$index],
                'project_id' => $project->id
            ]);
        }

        // Seed 15 Categories with sub_project_id
        $subProjects = SubProject::all();
        $categoryNames = [
            'Bahan Bangunan Dasar',
            'Besi dan Baja',
            'Kayu dan Bambu',
            'Cat dan Finishing',
            'Elektronik dan Kabel',
            'Pipa dan Fitting',
            'Keramik dan Granit',
            'Hardware dan Tools',
            'Insulation Material',
            'Roofing Material',
            'Door and Window',
            'Plumbing Fixtures',
            'HVAC Equipment',
            'Safety Equipment',
            'Miscellaneous'
        ];

        $createdCategories = [];
        foreach ($categoryNames as $index => $categoryName) {
            $subProject = $subProjects[$index % $subProjects->count()];
            $category = Category::firstOrCreate([
                'name' => $categoryName,
                'sub_project_id' => $subProject->id
            ]);
            $createdCategories[] = $category;
        }

        // Seed 20 Materials
        $materialData = [
            // Bahan Bangunan Dasar
            ['name' => 'Semen Portland Type I', 'unit' => 'sak'],
            ['name' => 'Pasir Beton Cor', 'unit' => 'm3'],
            ['name' => 'Kerikil Split 1-2 cm', 'unit' => 'm3'],
            ['name' => 'Batu Bata Merah', 'unit' => 'biji'],
            
            // Besi dan Baja
            ['name' => 'Besi Beton Ulir 10mm', 'unit' => 'batang'],
            ['name' => 'Besi Beton Polos 12mm', 'unit' => 'batang'],
            ['name' => 'Baja Ringan C75', 'unit' => 'batang'],
            ['name' => 'Kawat Bendrat', 'unit' => 'kg'],
            
            // Kayu dan Bambu
            ['name' => 'Kayu Meranti 5/7', 'unit' => 'batang'],
            ['name' => 'Triplek 9mm', 'unit' => 'lembar'],
            
            // Cat dan Finishing
            ['name' => 'Cat Tembok Putih Avitex', 'unit' => 'kaleng'],
            ['name' => 'Cat Besi Anti Karat', 'unit' => 'kaleng'],
            
            // Elektronik dan Kabel
            ['name' => 'Kabel NYA 2.5mm2', 'unit' => 'meter'],
            ['name' => 'Saklar Tunggal Panasonic', 'unit' => 'buah'],
            ['name' => 'Stop Kontak Universal', 'unit' => 'buah'],
            
            // Pipa dan Fitting
            ['name' => 'Pipa PVC 4 inch', 'unit' => 'batang'],
            ['name' => 'Elbow PVC 90 derajat', 'unit' => 'buah'],
            
            // Keramik dan Granit
            ['name' => 'Keramik Lantai 40x40', 'unit' => 'm2'],
            ['name' => 'Lem Keramik', 'unit' => 'sak'],
            
            // Hardware dan Tools
            ['name' => 'Paku Beton 3 inch', 'unit' => 'kg']
        ];

        foreach ($materialData as $index => $material) {
            $category = $createdCategories[$index % count($createdCategories)];
            $subProject = $subProjects[$index % $subProjects->count()];
            
            Material::firstOrCreate([
                'name' => $material['name']
            ], [
                'unit' => $material['unit'],
                'category_id' => $category->id,
                'sub_project_id' => $subProject->id
            ]);
        }
    }
}
