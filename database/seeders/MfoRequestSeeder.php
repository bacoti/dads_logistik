<?php

namespace Database\Seeders;

use App\Models\MfoRequest;
use App\Models\User;
use App\Models\Project;
use App\Models\SubProject;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class MfoRequestSeeder extends Seeder
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

        $created = 0;

        for ($i = 0; $i < 150; $i++) {
            $user = $users->random();
            $project = $projects->random();

            $subProjectCollection = $subProjects->where('project_id', $project->id);
            $subProject = $subProjectCollection->isNotEmpty() ? $subProjectCollection->random() : ($subProjects->isNotEmpty() ? $subProjects->random() : null);

            $requestDate = $faker->dateTimeBetween('-6 months', 'now');

            // status probabilities: pending 60%, reviewed 25%, approved 10%, rejected 5%
            $r = $faker->randomFloat(2, 0, 1);
            if ($r < 0.6) {
                $status = 'pending';
            } elseif ($r < 0.85) {
                $status = 'reviewed';
            } elseif ($r < 0.95) {
                $status = 'approved';
            } else {
                $status = 'rejected';
            }

            $documentPath = 'mfo-requests/' . $user->id . '/' . $faker->bothify('doc-#####') . '.pdf';

            $reviewedAt = null;
            $reviewedBy = null;
            $adminNotes = null;

            if ($status !== 'pending') {
                $reviewedAt = Carbon::now()->subDays($faker->numberBetween(0, 30));
                $possibleReviewers = $users->where('id', '!=', $user->id);
                $reviewer = $possibleReviewers->isNotEmpty() ? $possibleReviewers->random() : $users->random();
                $reviewedBy = $reviewer->id;
                $adminNotes = $faker->sentence(6);
            }

            MfoRequest::create([
                'user_id' => $user->id,
                'project_id' => $project->id,
                'sub_project_id' => $subProject ? $subProject->id : null,
                'project_location' => $faker->city,
                'cluster' => $faker->randomElement(['Cluster A', 'Cluster B', 'Zona Utara', 'Zona Selatan']),
                'request_date' => $requestDate->format('Y-m-d'),
                'description' => $faker->paragraph(3),
                'document_path' => $documentPath,
                'status' => $status,
                'admin_notes' => $adminNotes,
                'reviewed_at' => $reviewedAt,
                'reviewed_by' => $reviewedBy,
            ]);

            $created++;
        }

        echo "MfoRequestSeeder selesai. Dibuat: {$created} entri.\n";
    }
}
