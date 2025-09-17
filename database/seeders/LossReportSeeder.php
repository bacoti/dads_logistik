<?php

namespace Database\Seeders;

use App\Models\LossReport;
use App\Models\User;
use App\Models\Project;
use App\Models\SubProject;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class LossReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $users = User::all();
        $projects = Project::all();
        $subProjects = SubProject::all();

        if ($users->isEmpty() || $projects->isEmpty()) {
            echo "Master data missing: run master data seeders first.\n";
            return;
        }

        $materialTypes = ['Batu Bata', 'Semen', 'Pasir', 'Besi', 'Kayu', 'Keramik', 'Pipa', 'Kabel'];
        $clusters = ['Cluster A','Cluster B','Cluster C','Zona Utara','Zona Selatan','Zona Timur'];

        $created = 0;

        for ($i = 0; $i < 150; $i++) {
            $user = $users->random();
            $project = $projects->random();

            $subProjectCollection = $subProjects->where('project_id', $project->id);
            $subProject = $subProjectCollection->isNotEmpty() ? $subProjectCollection->random() : ($subProjects->isNotEmpty() ? $subProjects->random() : null);

            // loss date within last 6 months
            $lossDate = Carbon::now()->subDays($faker->numberBetween(0, 180))->format('Y-m-d');

            // Status probabilities matched to migration enum: pending, reviewed, completed
            $r = $faker->randomFloat(2, 0, 1);
            if ($r < 0.7) {
                $status = 'pending';
            } elseif ($r < 0.9) {
                $status = 'reviewed';
            } else {
                $status = 'completed';
            }

            $materialType = $faker->randomElement($materialTypes);
            $chronology = $faker->paragraph(2);
            $additionalNotes = $faker->optional(0.7)->sentence(10);

            $supportingPath = 'loss-reports/' . $user->id . '/' . Carbon::parse($lossDate)->format('Y-m-d') . '/' . $faker->bothify('doc-#####') . '.pdf';

            $reviewedAt = null;
            $reviewedBy = null;
            $adminNotes = null;

            if ($status !== 'pending') {
                $reviewedAt = Carbon::now()->subDays($faker->numberBetween(0, 60));
                $possibleReviewers = $users->where('id', '!=', $user->id);
                $reviewer = $possibleReviewers->isNotEmpty() ? $possibleReviewers->random() : $users->random();
                $reviewedBy = $reviewer->id;
                $adminNotes = $faker->sentence(8);
            }

            LossReport::create([
                'user_id' => $user->id,
                'project_id' => $project->id,
                'sub_project_id' => $subProject ? $subProject->id : null,
                'project_location' => $faker->city(),
                'cluster' => $faker->randomElement($clusters),
                'loss_date' => $lossDate,
                'material_type' => $materialType,
                'loss_chronology' => $chronology,
                'additional_notes' => $additionalNotes,
                'supporting_document_path' => $supportingPath,
                'status' => $status,
                'admin_notes' => $adminNotes,
                'reviewed_at' => $reviewedAt,
                'reviewed_by' => $reviewedBy,
            ]);

            $created++;
        }

        echo "LossReportSeeder selesai. Dibuat: {$created} laporan kehilangan.\n";
    }
}
