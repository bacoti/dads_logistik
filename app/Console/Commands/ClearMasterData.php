<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\SubProject;
use App\Models\Category;
use App\Models\Material;

class ClearMasterData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:master-data {--dry-run : Show what would be done without making changes} {--type=* : Specific types to clear (vendors,projects,subprojects,categories,materials)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear master data (vendors, projects, sub projects, categories, materials)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking master data to clear...');

        $isDryRun = $this->option('dry-run');
        $types = $this->option('type');

        // Show current counts
        $counts = [
            'vendors' => Vendor::count(),
            'projects' => Project::count(),
            'subprojects' => SubProject::count(),
            'categories' => Category::count(),
            'materials' => Material::count(),
        ];

        $this->info('Current data counts:');
        foreach ($counts as $type => $count) {
            $this->line("  {$type}: {$count}");
        }

        $totalItems = array_sum($counts);
        if ($totalItems == 0) {
            $this->info('No data to clear.');
            return;
        }

        // If no specific types specified, ask what to clear
        if (empty($types)) {
            $types = $this->choice(
                'What data do you want to clear?',
                [
                    'all' => 'Clear all master data',
                    'materials-only' => 'Clear only materials',
                    'categories-only' => 'Clear only categories',
                    'materials-categories' => 'Clear materials and categories',
                    'custom' => 'Choose specific types'
                ],
                'materials-categories',
                null,
                true
            );

            if (in_array('custom', $types)) {
                $types = $this->choice(
                    'Select which types to clear:',
                    ['vendors', 'projects', 'subprojects', 'categories', 'materials'],
                    null,
                    null,
                    true
                );
            }
        }

        // Process the selection
        if (in_array('all', $types)) {
            $types = ['materials', 'categories', 'subprojects', 'projects', 'vendors'];
        } elseif (in_array('materials-only', $types)) {
            $types = ['materials'];
        } elseif (in_array('categories-only', $types)) {
            $types = ['categories'];
        } elseif (in_array('materials-categories', $types)) {
            $types = ['materials', 'categories'];
        }

        $this->info('Selected types to clear: ' . implode(', ', $types));

        if (!$isDryRun) {
            if (!$this->confirm('Are you sure you want to clear this data? This action cannot be undone.')) {
                $this->info('Operation cancelled.');
                return;
            }
        }

        // Clear in correct order (dependencies first)
        $deletionOrder = ['materials', 'categories', 'subprojects', 'projects', 'vendors'];
        $deleted = [];

        foreach ($deletionOrder as $type) {
            if (in_array($type, $types)) {
                $count = $this->clearDataType($type, $isDryRun);
                if ($count > 0) {
                    $deleted[$type] = $count;
                }
            }
        }

        if ($isDryRun) {
            $this->info('');
            $this->info('This was a dry run. Use the command without --dry-run to actually clear the data.');
        } else {
            $this->info('');
            $this->info('Master data has been cleared!');
        }

        if (!empty($deleted)) {
            $this->info('Summary:');
            foreach ($deleted as $type => $count) {
                $status = $isDryRun ? 'Would delete' : 'Deleted';
                $this->line("  {$status} {$count} {$type}");
            }
        }
    }

    private function clearDataType($type, $isDryRun)
    {
        switch ($type) {
            case 'materials':
                $count = Material::count();
                if ($count > 0) {
                    $this->info("Clearing {$count} materials...");
                    if (!$isDryRun) {
                        // Check if there are transaction details that reference materials
                        $transactionDetailsCount = \DB::table('transaction_details')->count();
                        if ($transactionDetailsCount > 0) {
                            $this->warn("Found {$transactionDetailsCount} transaction details. Clearing them first...");
                            \DB::table('transaction_details')->delete();
                        }

                        // Disable foreign key checks temporarily
                        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                        Material::truncate();
                        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                    }
                }
                return $count;

            case 'categories':
                $count = Category::count();
                if ($count > 0) {
                    $this->info("Clearing {$count} categories...");
                    if (!$isDryRun) {
                        // Check if materials reference categories first
                        $materialsCount = Material::count();
                        if ($materialsCount > 0) {
                            $this->warn("Materials still exist. Clearing materials first...");
                            $this->clearDataType('materials', $isDryRun);
                        }

                        // Disable foreign key checks temporarily
                        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                        Category::truncate();
                        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                    }
                }
                return $count;

            case 'subprojects':
                $count = SubProject::count();
                if ($count > 0) {
                    $this->info("Clearing {$count} sub projects...");
                    if (!$isDryRun) {
                        SubProject::truncate();
                    }
                }
                return $count;

            case 'projects':
                $count = Project::count();
                if ($count > 0) {
                    $this->info("Clearing {$count} projects...");
                    if (!$isDryRun) {
                        Project::truncate();
                    }
                }
                return $count;

            case 'vendors':
                $count = Vendor::count();
                if ($count > 0) {
                    $this->info("Clearing {$count} vendors...");
                    if (!$isDryRun) {
                        Vendor::truncate();
                    }
                }
                return $count;

            default:
                $this->error("Unknown type: {$type}");
                return 0;
        }
    }
}
