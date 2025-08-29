<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\SubProject;
use App\Models\Material;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TransactionReceivingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Get all required master data
        $users = User::all();
        $vendors = Vendor::all();
        $projects = Project::all();
        $subProjects = SubProject::all();
        $materials = Material::all();

        // Locations in Indonesia
        $locations = [
            'Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Timur', 'Jakarta Barat', 'Jakarta Utara',
            'Surabaya', 'Bandung', 'Medan', 'Semarang', 'Makassar',
            'Palembang', 'Tangerang', 'Depok', 'Bekasi', 'Bogor',
            'Yogyakarta', 'Malang', 'Solo', 'Balikpapan', 'Banjarmasin'
        ];

        $clusters = [
            'Cluster A', 'Cluster B', 'Cluster C', 'Cluster D', 'Cluster E',
            'Zona Utara', 'Zona Selatan', 'Zona Timur', 'Zona Barat', 'Zona Tengah'
        ];

        // Generate 250 receiving transactions
        for ($i = 1; $i <= 250; $i++) {
            $vendor = $vendors->random();
            $project = $projects->random();
            $subProject = $subProjects->where('project_id', $project->id)->random();
            $user = $users->random();

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => 'penerimaan', // receiving transaction using correct enum value
                'transaction_date' => $faker->dateTimeBetween('-6 months', 'now'),
                'vendor_id' => $vendor->id,
                'project_id' => $project->id,
                'sub_project_id' => $subProject->id,
                'location' => $faker->randomElement($locations),
                'cluster' => $faker->randomElement($clusters),
                'site_id' => 'SITE-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'notes' => $faker->sentence(10),
                'proof_path' => null // You can add file paths if needed
            ]);

            // Create transaction details (1-5 materials per transaction)
            $materialCount = $faker->numberBetween(1, 5);
            $selectedMaterials = $materials->random($materialCount);

            foreach ($selectedMaterials as $material) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'material_id' => $material->id,
                    'quantity' => $faker->numberBetween(1, 100)
                ]);
            }
        }
    }
}
