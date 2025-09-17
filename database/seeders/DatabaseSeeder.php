<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // Create additional test users
        User::factory(5)->create();

        // Run master data seeder
        $this->call([
            ComprehensiveMasterDataSeeder::class,
            TransactionReceivingSeeder::class,
            AllTransactionTypesSeeder::class,
            // Bulk realistic transactions (150)
            BulkTransactionSeeder::class,
            // Monthly reports test data
            MonthlyReportSeeder::class,
            LossReportSeeder::class,
            // MFO requests test data
            MfoRequestSeeder::class,
            // Bulk documents for document management (70)
            BulkDocumentSeeder::class,
        ]);
    }
}
