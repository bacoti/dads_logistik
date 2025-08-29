<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PoMaterial;
use App\Models\User;
use App\Models\Project;
use App\Models\SubProject;
use Faker\Factory as Faker;

class PoMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get PO users (users with 'po' role)
        $poUsers = User::where('role', 'po')->get();
        $projects = Project::all();
        $subProjects = SubProject::all();

        if ($poUsers->isEmpty() || $projects->isEmpty() || $subProjects->isEmpty()) {
            $this->command->error('Please seed users, projects, and sub-projects first!');
            return;
        }

        $this->command->info('Creating PO Materials...');

        // Material types for realistic descriptions
        $materialTypes = [
            'Semen Portland',
            'Besi Beton',
            'Pasir Halus',
            'Kerikil',
            'Cat Tembok',
            'Genteng Keramik',
            'Pipa PVC',
            'Kabel Listrik',
            'Batu Bata',
            'Kayu Meranti',
            'Aspal Hotmix',
            'Baja Ringan',
            'Keramik Lantai',
            'Seng Gelombang',
            'Triplek'
        ];

        $suppliers = [
            'PT Semen Indonesia',
            'CV Bangunan Jaya',
            'Toko Material Maju',
            'PT Besi Berkah',
            'UD Material Sejahtera',
            'CV Konstruksi Prima',
            'Toko Bahan Bangunan',
            'PT Supplier Material'
        ];

        $locations = [
            'Jakarta Barat',
            'Jakarta Selatan',
            'Jakarta Pusat',
            'Jakarta Timur',
            'Jakarta Utara',
            'Tangerang',
            'Bekasi',
            'Bogor',
            'Depok'
        ];

        // Create 15 PO Materials with different statuses
        for ($i = 0; $i < 15; $i++) {
            $project = $projects->random();
            $subProject = $subProjects->where('project_id', $project->id)->first();

            if (!$subProject) {
                $subProject = $subProjects->random();
            }

            $materialType = $faker->randomElement($materialTypes);
            $quantity = $faker->numberBetween(10, 1000);
            $unit = $faker->randomElement(['kg', 'ton', 'pcs', 'm3', 'meter', 'sak', 'lembar']);

            // Status distribution: 60% pending, 25% approved, 15% rejected
            $statusRandom = $faker->numberBetween(1, 100);
            if ($statusRandom <= 60) {
                $status = 'pending';
            } elseif ($statusRandom <= 85) {
                $status = 'approved';
            } else {
                $status = 'rejected';
            }

            PoMaterial::create([
                'po_number' => 'POM/' . date('Y') . '/' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'user_id' => $poUsers->random()->id,
                'supplier' => $faker->randomElement($suppliers),
                'release_date' => $faker->dateTimeBetween('-30 days', '+30 days'),
                'location' => $faker->randomElement($locations),
                'project_id' => $project->id,
                'sub_project_id' => $subProject->id,
                'quantity' => $quantity,
                'unit' => $unit,
                'description' => $materialType . ' kualitas tinggi untuk konstruksi. ' . $faker->sentence(),
                'notes' => $faker->boolean(70) ? 'Catatan: ' . $faker->sentence() : null,
                'status' => $status,
                'created_at' => $faker->dateTimeBetween('-60 days', 'now'),
                'updated_at' => $faker->dateTimeBetween('-30 days', 'now'),
            ]);

            $this->command->info('Created PO Material #' . ($i + 1) . ' with status: ' . $status);
        }

        $this->command->info('PoMaterialSeeder completed successfully!');

        // Show summary
        $pending = PoMaterial::where('status', 'pending')->count();
        $approved = PoMaterial::where('status', 'approved')->count();
        $rejected = PoMaterial::where('status', 'rejected')->count();

        $this->command->info('Summary:');
        $this->command->info('- Pending: ' . $pending);
        $this->command->info('- Approved: ' . $approved);
        $this->command->info('- Rejected: ' . $rejected);
    }
}
