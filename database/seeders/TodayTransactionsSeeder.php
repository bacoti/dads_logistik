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

class TodayTransactionsSeeder extends Seeder
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

        $clusters = [
            'Cluster A', 'Cluster B', 'Cluster C', 'Zona Utara', 'Zona Selatan'
        ];

        $transactionTypes = ['penerimaan', 'pengambilan', 'pengembalian', 'peminjaman'];

        // Generate 10 transactions for today
        for ($i = 1; $i <= 10; $i++) {
            $user = $users->random();
            $vendor = $vendors->random();
            $project = $projects->random();
            $subProject = $subProjects->where('project_id', $project->id)->random();
            $transactionType = $faker->randomElement($transactionTypes);

            // Create transaction for today
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => $transactionType,
                'transaction_date' => today(), // Today's date
                'vendor_id' => $transactionType === 'penerimaan' ? $vendor->id : null,
                'project_id' => $project->id,
                'sub_project_id' => $subProject->id,
                'location' => 'Jakarta - Test Location',
                'cluster' => $faker->randomElement($clusters),
                'site_id' => 'TODAY-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'notes' => 'Transaksi hari ini untuk testing dashboard - ' . $faker->sentence(8),
                'proof_path' => null
            ]);

            // Create transaction details
            $materialCount = $faker->numberBetween(1, 3);
            $selectedMaterials = $materials->random($materialCount);

            foreach ($selectedMaterials as $material) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'material_id' => $material->id,
                    'quantity' => $faker->numberBetween(5, 25)
                ]);
            }
        }

        // Generate some for this week (but not today)
        for ($i = 1; $i <= 5; $i++) {
            $user = $users->random();
            $vendor = $vendors->random();
            $project = $projects->random();
            $subProject = $subProjects->where('project_id', $project->id)->random();
            $transactionType = $faker->randomElement($transactionTypes);

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => $transactionType,
                'transaction_date' => now()->subDays($faker->numberBetween(1, 6)), // This week but not today
                'vendor_id' => $transactionType === 'penerimaan' ? $vendor->id : null,
                'project_id' => $project->id,
                'sub_project_id' => $subProject->id,
                'location' => 'Jakarta - Test Location',
                'cluster' => $faker->randomElement($clusters),
                'site_id' => 'WEEK-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'notes' => 'Transaksi minggu ini untuk testing dashboard - ' . $faker->sentence(8),
                'proof_path' => null
            ]);

            $materialCount = $faker->numberBetween(1, 3);
            $selectedMaterials = $materials->random($materialCount);

            foreach ($selectedMaterials as $material) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'material_id' => $material->id,
                    'quantity' => $faker->numberBetween(5, 25)
                ]);
            }
        }

        echo "Seeder berhasil! Dibuat:\n";
        echo "- 10 transaksi untuk hari ini\n";
        echo "- 5 transaksi untuk minggu ini\n";
    }
}
