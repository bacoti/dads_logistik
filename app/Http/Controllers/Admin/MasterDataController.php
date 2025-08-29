<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\Material;
use App\Models\Category;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function index()
    {
        $activeTab = request('tab', 'vendors');

        // Get data based on active tab and search
        $search = request('search');

        $vendors = Vendor::query()
            ->when($search && $activeTab === 'vendors', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('contact_person', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%');
            })
            ->withCount('transactions')
            ->orderBy('name')
            ->get();

        $projects = Project::query()
            ->with('subProjects')
            ->when($search && $activeTab === 'projects', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('code', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->withCount('transactions')
            ->orderBy('name')
            ->get();

        $categories = Category::query()
            ->when($search && $activeTab === 'categories', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->withCount('materials')
            ->orderBy('name')
            ->get();

        $materials = Material::query()
            ->with('category')
            ->when($search && $activeTab === 'materials', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('unit', 'like', '%' . $search . '%')
                      ->orWhereHas('category', function ($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      });
            })
            ->orderBy('name')
            ->get();

        return view('admin.master-data.index', compact(
            'vendors',
            'projects',
            'categories',
            'materials',
            'activeTab',
            'search'
        ));
    }

    // Vendor CRUD methods
    public function storeVendor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        Vendor::create($validated);

        return redirect()->route('admin.master-data.index', ['tab' => 'vendors'])
            ->with('success', 'Vendor berhasil ditambahkan!');
    }

    public function updateVendor(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $vendor->update($validated);

        return redirect()->route('admin.master-data.index', ['tab' => 'vendors'])
            ->with('success', 'Vendor berhasil diperbarui!');
    }

    public function deleteVendor(Vendor $vendor)
    {
        if ($vendor->transactions()->count() > 0) {
            return redirect()->route('admin.master-data.index', ['tab' => 'vendors'])
                ->with('error', 'Vendor tidak dapat dihapus karena masih digunakan dalam transaksi.');
        }

        $vendor->delete();

        return redirect()->route('admin.master-data.index', ['tab' => 'vendors'])
            ->with('success', 'Vendor berhasil dihapus!');
    }

    // Project CRUD methods
    public function storeProject(Request $request)
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

        return redirect()->route('admin.master-data.index', ['tab' => 'projects'])
            ->with('success', 'Proyek berhasil ditambahkan!');
    }

    public function updateProject(Request $request, Project $project)
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

        return redirect()->route('admin.master-data.index', ['tab' => 'projects'])
            ->with('success', 'Proyek berhasil diperbarui!');
    }

    public function deleteProject(Project $project)
    {
        if ($project->transactions()->count() > 0) {
            return redirect()->route('admin.master-data.index', ['tab' => 'projects'])
                ->with('error', 'Proyek tidak dapat dihapus karena masih digunakan dalam transaksi.');
        }

        $project->subProjects()->delete();
        $project->delete();

        return redirect()->route('admin.master-data.index', ['tab' => 'projects'])
            ->with('success', 'Proyek berhasil dihapus!');
    }

    // Category CRUD methods
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('admin.master-data.index', ['tab' => 'categories'])
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('admin.master-data.index', ['tab' => 'categories'])
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function deleteCategory(Category $category)
    {
        if ($category->materials()->count() > 0) {
            return redirect()->route('admin.master-data.index', ['tab' => 'categories'])
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh material.');
        }

        $category->delete();

        return redirect()->route('admin.master-data.index', ['tab' => 'categories'])
            ->with('success', 'Kategori berhasil dihapus!');
    }

    // Material CRUD methods
    public function storeMaterial(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        Material::create($validated);

        return redirect()->route('admin.master-data.index', ['tab' => 'materials'])
            ->with('success', 'Material berhasil ditambahkan!');
    }

    public function updateMaterial(Request $request, Material $material)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $material->update($validated);

        return redirect()->route('admin.master-data.index', ['tab' => 'materials'])
            ->with('success', 'Material berhasil diperbarui!');
    }

    public function deleteMaterial(Material $material)
    {
        if ($material->transactionDetails()->count() > 0) {
            return redirect()->route('admin.master-data.index', ['tab' => 'materials'])
                ->with('error', 'Material tidak dapat dihapus karena masih digunakan dalam transaksi.');
        }

        $material->delete();

        return redirect()->route('admin.master-data.index', ['tab' => 'materials'])
            ->with('success', 'Material berhasil dihapus!');
    }
}
