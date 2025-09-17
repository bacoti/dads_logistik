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

class BulkTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Load master data
        $users = User::all();
        $vendors = Vendor::all();
        $projects = Project::all();
        $subProjects = SubProject::all();
        $materials = Material::all();

        if ($users->isEmpty() || $projects->isEmpty() || $materials->isEmpty()) {
            echo "Master data missing: please run master data seeders first.\n";
            return;
        }

        $locations = [
            'Jakarta Pusat','Jakarta Selatan','Jakarta Timur','Jakarta Barat','Jakarta Utara',
            'Surabaya','Bandung','Medan','Semarang','Makassar','Palembang','Tangerang','Depok','Bekasi','Bogor'
        ];

        $clusters = [
            'Cluster A','Cluster B','Cluster C','Zona Utara','Zona Selatan','Zona Timur','Zona Barat'
        ];

        $transactionTypes = ['penerimaan', 'pengambilan', 'pengembalian', 'peminjaman'];

        $created = 0;
        $detailsCreated = 0;

        // We'll create 150 transactions distributed over the last 6 months
        for ($i = 1; $i <= 150; $i++) {
            $user = $users->random();
            // Choose type with controlled probabilities:
            // penerimaan 50%, pengambilan 30%, pengembalian 10%, peminjaman 10%
            $r = $faker->randomFloat(2, 0, 1);
            if ($r < 0.5) {
                $type = 'penerimaan';
            } elseif ($r < 0.8) {
                $type = 'pengambilan';
            } elseif ($r < 0.9) {
                $type = 'pengembalian';
            } else {
                $type = 'peminjaman';
            }

            $project = $projects->random();
            $subProjectCollection = $subProjects->where('project_id', $project->id);
            if ($subProjectCollection->isEmpty()) {
                // fallback: pick any subproject if available, otherwise null
                $subProject = $subProjects->isNotEmpty() ? $subProjects->random() : null;
            } else {
                $subProject = $subProjectCollection->random();
            }

            $transactionDate = $faker->dateTimeBetween('-6 months', 'now');

            // Safe vendor selection: only pick vendor when penerimaan and vendors available
            $vendorId = null;
            $vendorName = null;
            if ($type === 'penerimaan' && $vendors->isNotEmpty()) {
                $vendor = $vendors->random();
                $vendorId = $vendor->id;
                $vendorName = $vendor->name ?? null;
            }

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => $type,
                'transaction_date' => $transactionDate,
                'vendor_id' => $vendorId,
                'vendor_name' => $vendorName,
                'project_id' => $project->id,
                'sub_project_id' => $subProject ? $subProject->id : null,
                'location' => $faker->randomElement($locations),
                'cluster' => $faker->randomElement($clusters),
                'site_id' => strtoupper($faker->bothify('SITE-??-###')),
                'notes' => $faker->sentence(8),
                'proof_path' => null,
                'delivery_order_no' => $type === 'penerimaan' ? 'DO-' . strtoupper($faker->bothify('??-#####')) : null,
                'delivery_note_no' => $type === 'penerimaan' ? 'DN-' . strtoupper($faker->bothify('??-#####')) : null,
                'delivery_return_no' => $type === 'pengembalian' ? 'DR-' . strtoupper($faker->bothify('??-#####')) : null,
                'return_destination' => $type === 'pengembalian' ? $faker->randomElement($locations) : null,
            ]);

            $created++;

            // Create 1-6 details per transaction, quantities tuned by transaction type
            $detailCount = $faker->numberBetween(1, 6);
            $take = min($detailCount, $materials->count());
            $selectedMaterials = $materials->random($take);
            if (! $selectedMaterials instanceof \Illuminate\Support\Collection) {
                $selectedMaterials = collect([$selectedMaterials]);
            }

            foreach ($selectedMaterials as $material) {
                // Quantity distribution: penerimaan larger batches, pengambilan smaller
                if ($type === 'penerimaan') {
                    $qty = $faker->numberBetween(10, 200);
                } elseif ($type === 'pengambilan') {
                    $qty = $faker->numberBetween(1, 50);
                } elseif ($type === 'pengembalian') {
                    $qty = $faker->numberBetween(1, 80);
                } else {
                    $qty = $faker->numberBetween(1, 30);
                }

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'material_id' => $material->id,
                    'quantity' => $qty
                ]);

                $detailsCreated++;
            }
        }

        echo "BulkTransactionSeeder selesai. Transaksi dibuat: {$created}. Detail dibuat: {$detailsCreated}.\n";
    }
}
