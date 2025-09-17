<?php

namespace Database\Seeders;

use App\Models\MonthlyReport;
use App\Models\User;
use App\Models\Project;
use App\Models\SubProject;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class MonthlyReportSeeder extends Seeder
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

        $statuses = ['pending', 'reviewed', 'approved', 'rejected'];

        $created = 0;

        // Generate 150 monthly reports covering the last 12 months
        for ($i = 0; $i < 150; $i++) {
            $user = $users->random();
            $project = $projects->random();

            $subProjectCollection = $subProjects->where('project_id', $project->id);
            $subProject = $subProjectCollection->isNotEmpty() ? $subProjectCollection->random() : ($subProjects->isNotEmpty() ? $subProjects->random() : null);

            // Choose a report period within the last 12 months
            $monthsBack = $faker->numberBetween(0, 11);
            $period = Carbon::now()->subMonths($monthsBack)->format('Y-m');
            $reportDate = Carbon::createFromFormat('Y-m-d', Carbon::parse($period . '-01')->endOfMonth()->format('Y-m-d'));

            // Status probabilities: pending 60%, reviewed 25%, approved 10%, rejected 5%
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

            $excelPath = 'monthly-reports/' . $user->id . '/' . $period . '/' . $faker->bothify('report-#####') . '.xlsx';

            $reviewedAt = null;
            $reviewedBy = null;
            $adminNotes = null;

            if ($status !== 'pending') {
                $reviewedAt = Carbon::now()->subDays($faker->numberBetween(0, 30));
                // choose reviewer different from author if possible
                $possibleReviewers = $users->where('id', '!=', $user->id);
                $reviewer = $possibleReviewers->isNotEmpty() ? $possibleReviewers->random() : $users->random();
                $reviewedBy = $reviewer->id;
                $adminNotes = $faker->sentence(8);
            }

            MonthlyReport::create([
                'user_id' => $user->id,
                'report_date' => $reportDate->format('Y-m-d'),
                'report_period' => $period,
                'project_id' => $project->id,
                'sub_project_id' => $subProject ? $subProject->id : null,
                'project_location' => $faker->randomElement(['Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang']),
                'notes' => $faker->paragraph(2),
                'excel_file_path' => $excelPath,
                'status' => $status,
                'admin_notes' => $adminNotes,
                'reviewed_at' => $reviewedAt,
                'reviewed_by' => $reviewedBy,
            ]);

            $created++;
        }

        echo "MonthlyReportSeeder selesai. Dibuat: {$created} laporan.\n";
    }
}
