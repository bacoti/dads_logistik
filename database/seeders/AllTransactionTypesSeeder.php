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

class AllTransactionTypesSeeder extends Seeder
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

        $transactionTypes = ['penerimaan', 'pengambilan', 'pengembalian', 'peminjaman'];
        $siteCounter = 1;

        // Generate additional transactions with various types (150 more transactions)
        for ($i = 1; $i <= 150; $i++) {
            $vendor = $vendors->random();
            $project = $projects->random();
            $subProject = $subProjects->where('project_id', $project->id)->random();
            $user = $users->random();
            $transactionType = $faker->randomElement($transactionTypes);

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => $transactionType,
                'transaction_date' => $faker->dateTimeBetween('-6 months', 'now'),
                'vendor_id' => $transactionType === 'penerimaan' ? $vendor->id : null, // Only penerimaan needs vendor
                'project_id' => $project->id,
                'sub_project_id' => $subProject->id,
                'location' => $faker->randomElement($locations),
                'cluster' => $faker->randomElement($clusters),
                'site_id' => 'SITE-' . str_pad($siteCounter + 250, 4, '0', STR_PAD_LEFT), // Continue from previous seeder
                'notes' => $this->generateNotesBasedOnType($transactionType, $faker),
                'proof_path' => null
            ]);

            // Create transaction details (1-4 materials per transaction)
            $materialCount = $faker->numberBetween(1, 4);
            $selectedMaterials = $materials->random($materialCount);

            foreach ($selectedMaterials as $material) {
                $quantity = $this->generateQuantityBasedOnType($transactionType, $faker);
                
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'material_id' => $material->id,
                    'quantity' => $quantity
                ]);
            }

            $siteCounter++;
        }
    }

    private function generateNotesBasedOnType($type, $faker)
    {
        switch ($type) {
            case 'penerimaan':
                return 'Material diterima dalam kondisi baik. ' . $faker->sentence(8);
            case 'pengambilan':
                return 'Material diambil untuk keperluan proyek. ' . $faker->sentence(8);
            case 'pengembalian':
                return 'Material dikembalikan karena kelebihan stok. ' . $faker->sentence(8);
            case 'peminjaman':
                return 'Material dipinjam untuk keperluan sementara. ' . $faker->sentence(8);
            default:
                return $faker->sentence(10);
        }
    }

    private function generateQuantityBasedOnType($type, $faker)
    {
        switch ($type) {
            case 'penerimaan':
                return $faker->numberBetween(10, 100); // Larger quantities for receiving
            case 'pengambilan':
                return $faker->numberBetween(5, 50);   // Medium quantities for taking
            case 'pengembalian':
                return $faker->numberBetween(1, 20);   // Smaller quantities for returns
            case 'peminjaman':
                return $faker->numberBetween(1, 30);   // Small to medium for borrowing
            default:
                return $faker->numberBetween(1, 50);
        }
    }
}
