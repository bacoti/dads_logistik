<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Material;
use App\Models\SubProject;
use App\Models\Project;

class FixMasterDataRelations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:master-data-relations {--dry-run : Show what would be done without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix categories and materials that are missing sub_project_id relations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking master data relations...');

        $isDryRun = $this->option('dry-run');

        // Get counts
        $categoriesWithoutSubProject = Category::whereNull('sub_project_id')->count();
        $materialsWithoutSubProject = Material::whereNull('sub_project_id')->count();

        $this->info("Found {$categoriesWithoutSubProject} categories without sub_project_id");
        $this->info("Found {$materialsWithoutSubProject} materials without sub_project_id");

        if ($categoriesWithoutSubProject == 0 && $materialsWithoutSubProject == 0) {
            $this->info('All data already has proper relations. Nothing to fix.');
            return;
        }

        // Check if we have sub projects to assign to
        $subProjects = SubProject::with('project')->get();
        if ($subProjects->isEmpty()) {
            $this->error('No sub projects found. Please create some sub projects first.');
            return;
        }

        $this->info('Available sub projects:');
        foreach ($subProjects as $subProject) {
            $this->line("  - {$subProject->name} (ID: {$subProject->id}) - Project: {$subProject->project->name}");
        }

        // Let user choose assignment strategy
        $strategy = $this->choice(
            'How do you want to assign categories and materials to sub projects?',
            [
                'assign_to_first' => 'Assign all to first sub project',
                'assign_evenly' => 'Distribute evenly across all sub projects',
                'assign_interactively' => 'Choose for each category/material (only for small datasets)',
                'create_default' => 'Create a default sub project for orphaned data'
            ],
            'create_default'
        );

        if ($strategy === 'create_default') {
            $this->handleCreateDefaultStrategy($isDryRun);
        } elseif ($strategy === 'assign_to_first') {
            $this->handleAssignToFirstStrategy($subProjects, $isDryRun);
        } elseif ($strategy === 'assign_evenly') {
            $this->handleAssignEvenlyStrategy($subProjects, $isDryRun);
        } elseif ($strategy === 'assign_interactively') {
            if ($categoriesWithoutSubProject + $materialsWithoutSubProject > 20) {
                $this->error('Too many items for interactive mode (max 20). Please choose another strategy.');
                return;
            }
            $this->handleInteractiveStrategy($subProjects, $isDryRun);
        }

        if ($isDryRun) {
            $this->info('');
            $this->info('This was a dry run. Use the command without --dry-run to actually make the changes.');
        } else {
            $this->info('');
            $this->info('Master data relations have been fixed!');
        }
    }

    private function handleCreateDefaultStrategy($isDryRun)
    {
        // Check if we have a default project
        $defaultProject = Project::where('name', 'like', '%Default%')->first();

        if (!$defaultProject) {
            $this->info('Creating default project for orphaned data...');
            if (!$isDryRun) {
                $defaultProject = Project::create([
                    'name' => 'Default Project',
                    'code' => 'DEF001'
                ]);
                $this->info("Created default project: {$defaultProject->name} (ID: {$defaultProject->id})");
            } else {
                $this->info('[DRY RUN] Would create default project');
            }
        }

        // Check if we have a default sub project
        $defaultSubProject = null;
        if ($defaultProject) {
            $defaultSubProject = SubProject::where('project_id', $defaultProject->id)
                                           ->where('name', 'like', '%Default%')
                                           ->first();
        }

        if (!$defaultSubProject) {
            $this->info('Creating default sub project for orphaned data...');
            if (!$isDryRun && $defaultProject) {
                $defaultSubProject = SubProject::create([
                    'name' => 'Default Sub Project',
                    'project_id' => $defaultProject->id
                ]);
                $this->info("Created default sub project: {$defaultSubProject->name} (ID: {$defaultSubProject->id})");
            } else {
                $this->info('[DRY RUN] Would create default sub project');
            }
        }

        if ($defaultSubProject || $isDryRun) {
            $this->assignDataToSubProject($defaultSubProject, $isDryRun);
        }
    }

    private function handleAssignToFirstStrategy($subProjects, $isDryRun)
    {
        $firstSubProject = $subProjects->first();
        $this->info("Assigning all orphaned data to: {$firstSubProject->name}");
        $this->assignDataToSubProject($firstSubProject, $isDryRun);
    }

    private function handleAssignEvenlyStrategy($subProjects, $isDryRun)
    {
        $subProjectIds = $subProjects->pluck('id')->toArray();
        $subProjectCount = count($subProjectIds);

        // Assign categories
        $categories = Category::whereNull('sub_project_id')->get();
        $this->info("Distributing {$categories->count()} categories evenly...");

        foreach ($categories as $index => $category) {
            $assignedSubProjectId = $subProjectIds[$index % $subProjectCount];
            $assignedSubProject = $subProjects->find($assignedSubProjectId);

            $this->line("  Category '{$category->name}' -> {$assignedSubProject->name}");

            if (!$isDryRun) {
                $category->update(['sub_project_id' => $assignedSubProjectId]);
            }
        }

        // Assign materials
        $materials = Material::whereNull('sub_project_id')->get();
        $this->info("Distributing {$materials->count()} materials evenly...");

        foreach ($materials as $index => $material) {
            $assignedSubProjectId = $subProjectIds[$index % $subProjectCount];
            $assignedSubProject = $subProjects->find($assignedSubProjectId);

            $this->line("  Material '{$material->name}' -> {$assignedSubProject->name}");

            if (!$isDryRun) {
                $material->update(['sub_project_id' => $assignedSubProjectId]);
            }
        }
    }

    private function handleInteractiveStrategy($subProjects, $isDryRun)
    {
        // Handle categories
        $categories = Category::whereNull('sub_project_id')->get();
        foreach ($categories as $category) {
            $choices = $subProjects->mapWithKeys(function ($sp) {
                return [$sp->id => "{$sp->name} (Project: {$sp->project->name})"];
            })->toArray();

            $selectedSubProjectId = $this->choice(
                "Assign category '{$category->name}' to which sub project?",
                $choices
            );

            $selectedSubProject = $subProjects->find($selectedSubProjectId);
            $this->info("  Category '{$category->name}' -> {$selectedSubProject->name}");

            if (!$isDryRun) {
                $category->update(['sub_project_id' => $selectedSubProjectId]);
            }
        }

        // Handle materials
        $materials = Material::whereNull('sub_project_id')->get();
        foreach ($materials as $material) {
            $choices = $subProjects->mapWithKeys(function ($sp) {
                return [$sp->id => "{$sp->name} (Project: {$sp->project->name})"];
            })->toArray();

            $selectedSubProjectId = $this->choice(
                "Assign material '{$material->name}' ({$material->unit}) to which sub project?",
                $choices
            );

            $selectedSubProject = $subProjects->find($selectedSubProjectId);
            $this->info("  Material '{$material->name}' -> {$selectedSubProject->name}");

            if (!$isDryRun) {
                $material->update(['sub_project_id' => $selectedSubProjectId]);
            }
        }
    }

    private function assignDataToSubProject($subProject, $isDryRun)
    {
        if (!$subProject && !$isDryRun) {
            $this->error('No sub project provided');
            return;
        }

        // Update categories
        $categoriesToUpdate = Category::whereNull('sub_project_id')->count();
        $this->info("Assigning {$categoriesToUpdate} categories...");

        if (!$isDryRun) {
            Category::whereNull('sub_project_id')->update([
                'sub_project_id' => $subProject->id
            ]);
            $this->info("  ✓ Updated {$categoriesToUpdate} categories");
        } else {
            $this->info("  [DRY RUN] Would update {$categoriesToUpdate} categories");
        }

        // Update materials
        $materialsToUpdate = Material::whereNull('sub_project_id')->count();
        $this->info("Assigning {$materialsToUpdate} materials...");

        if (!$isDryRun) {
            Material::whereNull('sub_project_id')->update([
                'sub_project_id' => $subProject->id
            ]);
            $this->info("  ✓ Updated {$materialsToUpdate} materials");
        } else {
            $this->info("  [DRY RUN] Would update {$materialsToUpdate} materials");
        }
    }
}
