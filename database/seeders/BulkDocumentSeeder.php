<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Document;
use App\Models\User;

class BulkDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $users = User::all();
        if ($users->isEmpty()) {
            echo "No users found - run user seeders first.\n";
            return;
        }

        $categories = ['template', 'manual', 'form', 'document', 'other'];
        $mimeMap = [
            'template' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'xlsx'],
            'manual' => ['application/pdf', 'pdf'],
            'form' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'docx'],
            'document' => ['application/pdf', 'pdf'],
            'other' => ['application/zip', 'zip'],
        ];

        $created = 0;

        for ($i = 0; $i < 70; $i++) {
            $category = $faker->randomElement($categories);
            [$mime, $ext] = $mimeMap[$category];

            $uploader = $users->random();

            $fileBase = $faker->slug(3) . '-' . $faker->bothify('###');
            $fileName = $fileBase . '.' . $ext;
            $originalName = ucfirst(str_replace('-', ' ', $fileBase)) . '.' . $ext;
            $filePath = 'documents/' . $uploader->id . '/' . $fileName;
            $fileSize = $faker->numberBetween(1024, 5 * 1024 * 1024); // 1KB - 5MB

            Document::create([
                'title' => ucfirst($faker->words(3, true)),
                'description' => $faker->sentence(12),
                'file_name' => $fileName,
                'original_name' => $originalName,
                'file_path' => $filePath,
                'file_type' => $mime,
                'file_size' => $fileSize,
                'category' => $category,
                'is_active' => $faker->boolean(90),
                'download_count' => $faker->numberBetween(0, 200),
                'uploaded_by' => $uploader->id,
                'created_at' => now()->subDays($faker->numberBetween(0, 60)),
                'updated_at' => now()->subDays($faker->numberBetween(0, 30)),
            ]);

            $created++;
        }

        echo "BulkDocumentSeeder selesai. Dibuat: {$created} dokumen.\n";
    }
}
