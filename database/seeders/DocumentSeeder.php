<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Document;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure admin user exists
        $adminUser = \App\Models\User::where('role', 'admin')->first();
        if (!$adminUser) {
            $adminUser = \App\Models\User::create([
                'name' => 'Admin',
                'email' => 'admin@ptdads.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
        }

        $documents = [
            [
                'title' => 'Template Laporan Bulanan',
                'description' => 'Template standar untuk membuat laporan bulanan yang harus diisi oleh tim lapangan setiap bulan.',
                'category' => 'template',
                'file_name' => 'template_laporan_bulanan.xlsx',
                'original_name' => 'template_laporan_bulanan.xlsx',
                'file_path' => 'documents/template_laporan_bulanan.xlsx',
                'file_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'file_size' => 52480,
                'is_active' => true,
                'download_count' => 25,
                'uploaded_by' => $adminUser->id,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'title' => 'Form Request Material',
                'description' => 'Form yang harus diisi ketika membutuhkan material untuk proyek. Pastikan semua field terisi dengan lengkap.',
                'category' => 'form',
                'file_name' => 'form_request_material.docx',
                'original_name' => 'form_request_material.docx',
                'file_path' => 'documents/form_request_material.docx',
                'file_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'file_size' => 128945,
                'is_active' => true,
                'download_count' => 18,
                'uploaded_by' => $adminUser->id,
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],
            [
                'title' => 'Manual Penggunaan Alat Konstruksi',
                'description' => 'Panduan lengkap penggunaan alat-alat konstruksi yang tersedia di lapangan beserta prosedur keselamatan.',
                'category' => 'manual',
                'file_name' => 'manual_alat_konstruksi.pdf',
                'original_name' => 'manual_alat_konstruksi.pdf',
                'file_path' => 'documents/manual_alat_konstruksi.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 2048576,
                'is_active' => true,
                'download_count' => 42,
                'uploaded_by' => $adminUser->id,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'title' => 'Prosedur Keselamatan Kerja',
                'description' => 'Dokumen berisi prosedur dan standar keselamatan kerja yang harus dipatuhi oleh seluruh pekerja lapangan.',
                'category' => 'document',
                'file_name' => 'prosedur_keselamatan_kerja.pdf',
                'original_name' => 'prosedur_keselamatan_kerja.pdf',
                'file_path' => 'documents/prosedur_keselamatan_kerja.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 1536000,
                'is_active' => true,
                'download_count' => 67,
                'uploaded_by' => $adminUser->id,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(5),
            ],
            [
                'title' => 'Template Checklist Harian',
                'description' => 'Checklist yang harus diisi setiap hari untuk memastikan semua aktivitas berjalan sesuai standar.',
                'category' => 'template',
                'file_name' => 'checklist_harian.xlsx',
                'original_name' => 'checklist_harian.xlsx',
                'file_path' => 'documents/checklist_harian.xlsx',
                'file_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'file_size' => 45320,
                'is_active' => true,
                'download_count' => 35,
                'uploaded_by' => $adminUser->id,
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(6),
            ],
            [
                'title' => 'Kumpulan Form Administrasi',
                'description' => 'Arsip berisi semua form administrasi yang diperlukan untuk berbagai keperluan proyek.',
                'category' => 'other',
                'file_name' => 'form_administrasi.zip',
                'original_name' => 'form_administrasi.zip',
                'file_path' => 'documents/form_administrasi.zip',
                'file_type' => 'application/zip',
                'file_size' => 3145728,
                'is_active' => true,
                'download_count' => 12,
                'uploaded_by' => $adminUser->id,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
        ];

        foreach ($documents as $document) {
            Document::create($document);
        }
    }
}
