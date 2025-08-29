<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SubProject;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::query()
            ->with('subProjects')
            ->when(request('search'), function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                      ->orWhere('code', 'like', '%' . request('search') . '%')
                      ->orWhere('description', 'like', '%' . request('search') . '%');
            })
            ->orderBy('name')
            ->paginate(15);

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:projects',
            'description' => 'nullable|string',
        ]);

        $project = Project::create($validated);

        // Add sub projects if provided
        if ($request->has('sub_projects') && is_array($request->sub_projects)) {
            foreach ($request->sub_projects as $subProjectName) {
                if (!empty($subProjectName)) {
                    $project->subProjects()->create(['name' => $subProjectName]);
                }
            }
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'Proyek berhasil ditambahkan!');
    }

    public function show(Project $project)
    {
        $project->load(['subProjects', 'transactions']);
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $project->load('subProjects');
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:projects,code,' . $project->id,
            'description' => 'nullable|string',
        ]);

        $project->update($validated);

        // Update sub projects
        if ($request->has('sub_projects') && is_array($request->sub_projects)) {
            // Delete existing sub projects
            $project->subProjects()->delete();

            // Add new sub projects
            foreach ($request->sub_projects as $subProjectName) {
                if (!empty($subProjectName)) {
                    $project->subProjects()->create(['name' => $subProjectName]);
                }
            }
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'Proyek berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        // Check if project is used in transactions
        if ($project->transactions()->count() > 0) {
            return redirect()->route('admin.projects.index')
                ->with('error', 'Proyek tidak dapat dihapus karena masih digunakan dalam transaksi.');
        }

        // Delete sub projects first
        $project->subProjects()->delete();
        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Proyek berhasil dihapus!');
    }
}
