<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\SubProject;
use App\Models\Material;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterDataController extends Controller
{
    /**
     * Get initial data for master data management
     */
    public function init()
    {
        \Log::info('API MasterDataController::init() called');
        
        try {
            \Log::info('Attempting to query database tables...');
            
            // Verify tables exist by attempting to query them
            $vendors = Vendor::select('id', 'name')->orderBy('name')->get();
            \Log::info('Vendors loaded: ' . $vendors->count());
            
            $projects = Project::select('id', 'name', 'code')->orderBy('name')->get();
            \Log::info('Projects loaded: ' . $projects->count());
            
            // Categories are now sub-project specific, so we don't load them globally
            $response = [
                'success' => true,
                'vendors' => $vendors,
                'projects' => $projects,
                'message' => 'Data berhasil dimuat'
            ];
            
            \Log::info('Returning successful response from init()', ['vendors_count' => $vendors->count()]);
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error('Master Data Init Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store vendor
     */
    public function storeVendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:vendors,name'
        ], [
            'name.required' => 'Nama vendor harus diisi',
            'name.string' => 'Nama vendor harus berupa teks',
            'name.max' => 'Nama vendor tidak boleh lebih dari 255 karakter',
            'name.unique' => 'Nama vendor sudah ada, gunakan nama lain'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vendor = Vendor::create([
                'name' => trim($request->name)
            ]);

            \Log::info('Vendor created successfully: ' . $vendor->name);
            
            return response()->json([
                'success' => true,
                'vendor' => $vendor,
                'message' => 'Vendor berhasil ditambahkan'
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Store Vendor Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan vendor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store project
     */
    public function storeProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:projects,name'
        ], [
            'name.required' => 'Nama proyek harus diisi',
            'name.string' => 'Nama proyek harus berupa teks',
            'name.max' => 'Nama proyek tidak boleh lebih dari 255 karakter',
            'name.unique' => 'Nama proyek sudah ada, gunakan nama lain'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate unique code from name
            $cleanName = preg_replace('/[^A-Za-z0-9\s]/', '', trim($request->name));
            $nameWords = explode(' ', $cleanName);
            $codePrefix = '';
            
            // Take first 3 characters from each word, max 3 words
            foreach (array_slice($nameWords, 0, 3) as $word) {
                if (strlen($word) > 0) {
                    $codePrefix .= strtoupper(substr($word, 0, 1));
                }
            }
            
            // If code is too short, pad with first letters
            if (strlen($codePrefix) < 3) {
                $codePrefix = strtoupper(substr($cleanName, 0, 3));
            }
            
            // Generate unique code with counter
            $counter = Project::count() + 1;
            $code = $codePrefix . str_pad($counter, 3, '0', STR_PAD_LEFT);
            
            // Ensure code is unique
            while (Project::where('code', $code)->exists()) {
                $counter++;
                $code = $codePrefix . str_pad($counter, 3, '0', STR_PAD_LEFT);
            }

            $project = Project::create([
                'name' => trim($request->name),
                'code' => $code
            ]);

            \Log::info('Project created successfully: ' . $project->name . ' with code: ' . $project->code);

            return response()->json([
                'success' => true,
                'project' => $project,
                'message' => 'Proyek berhasil ditambahkan'
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Store Project Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan proyek: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store sub project
     */
    public function storeSubProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id'
        ], [
            'name.required' => 'Nama sub proyek harus diisi',
            'name.string' => 'Nama sub proyek harus berupa teks',
            'name.max' => 'Nama sub proyek tidak boleh lebih dari 255 karakter',
            'project_id.required' => 'Proyek harus dipilih',
            'project_id.exists' => 'Proyek tidak ditemukan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verify project exists
            $project = Project::findOrFail($request->project_id);
            
            // Check if sub project name already exists for this project
            $exists = SubProject::where('project_id', $request->project_id)
                               ->where('name', trim($request->name))
                               ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nama sub proyek sudah ada untuk proyek ini'
                ], 422);
            }

            $subProject = SubProject::create([
                'name' => trim($request->name),
                'project_id' => $request->project_id
            ]);

            \Log::info('Sub Project created successfully: ' . $subProject->name . ' for project: ' . $project->name);

            return response()->json([
                'success' => true,
                'subProject' => $subProject->load('project:id,name,code'),
                'message' => 'Sub proyek berhasil ditambahkan'
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Store Sub Project Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan sub proyek: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store category
     */
    public function storeCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sub_project_id' => 'required|exists:sub_projects,id'
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.string' => 'Nama kategori harus berupa teks',
            'name.max' => 'Nama kategori tidak boleh lebih dari 255 karakter',
            'sub_project_id.required' => 'Sub proyek harus dipilih',
            'sub_project_id.exists' => 'Sub proyek tidak ditemukan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if category name already exists for this sub project
            $exists = Category::where('sub_project_id', $request->sub_project_id)
                             ->where('name', trim($request->name))
                             ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nama kategori sudah ada untuk sub proyek ini'
                ], 422);
            }

            $category = Category::create([
                'name' => trim($request->name),
                'sub_project_id' => $request->sub_project_id
            ]);

            // Load sub project relationship
            $category->load('subProject:id,name,project_id');

            \Log::info('Category created successfully: ' . $category->name . ' for sub project: ' . $category->subProject->name);
            
            return response()->json([
                'success' => true,
                'category' => $category,
                'message' => 'Kategori berhasil ditambahkan'
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Store Category Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store material
     */
    public function storeMaterial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'sub_project_id' => 'required|exists:sub_projects,id'
        ], [
            'name.required' => 'Nama material harus diisi',
            'name.string' => 'Nama material harus berupa teks',
            'name.max' => 'Nama material tidak boleh lebih dari 255 karakter',
            'unit.required' => 'Satuan material harus diisi',
            'unit.string' => 'Satuan material harus berupa teks',
            'unit.max' => 'Satuan material tidak boleh lebih dari 50 karakter',
            'category_id.required' => 'Kategori harus dipilih',
            'category_id.exists' => 'Kategori tidak ditemukan',
            'sub_project_id.required' => 'Sub proyek harus dipilih',
            'sub_project_id.exists' => 'Sub proyek tidak ditemukan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verify category belongs to the sub project
            $category = Category::where('id', $request->category_id)
                              ->where('sub_project_id', $request->sub_project_id)
                              ->first();

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak sesuai dengan sub proyek yang dipilih'
                ], 422);
            }

            // Check if material name already exists for this sub project
            $exists = Material::where('sub_project_id', $request->sub_project_id)
                             ->where('name', trim($request->name))
                             ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nama material sudah ada untuk sub proyek ini'
                ], 422);
            }

            $material = Material::create([
                'name' => trim($request->name),
                'unit' => trim($request->unit),
                'category_id' => $request->category_id,
                'sub_project_id' => $request->sub_project_id
            ]);

            // Load relationships
            $material->load(['category:id,name', 'subProject:id,name,project_id']);

            \Log::info('Material created successfully: ' . $material->name . ' in category: ' . $category->name . ' for sub project: ' . $material->subProject->name);

            return response()->json([
                'success' => true,
                'material' => $material,
                'message' => 'Material berhasil ditambahkan'
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Store Material Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sub projects for a project
     */
    public function getSubProjects($projectId)
    {
        try {
            // Verify project exists
            $project = Project::findOrFail($projectId);
            
            $subProjects = SubProject::where('project_id', $projectId)
                                   ->select('id', 'name', 'project_id')
                                   ->orderBy('name')
                                   ->get();

            return response()->json([
                'success' => true,
                'subProjects' => $subProjects,
                'project' => [
                    'id' => $project->id,
                    'name' => $project->name,
                    'code' => $project->code
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Get Sub Projects Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat sub proyek: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get materials (all materials, not project-specific)
     */
    public function getMaterials($subProjectId = null)
    {
        try {
            $query = Material::with('category:id,name');

            if ($subProjectId) {
                // Verify sub project exists
                $subProject = SubProject::findOrFail($subProjectId);
                $query->where('sub_project_id', $subProjectId);
            }

            $materials = $query->select('id', 'name', 'unit', 'category_id', 'sub_project_id')
                              ->orderBy('name')
                              ->get();

            return response()->json([
                'success' => true,
                'materials' => $materials
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get categories for a sub project
     */
    public function getCategories($subProjectId)
    {
        try {
            // Verify sub project exists
            $subProject = SubProject::findOrFail($subProjectId);
            
            $categories = Category::where('sub_project_id', $subProjectId)
                                ->select('id', 'name', 'sub_project_id')
                                ->orderBy('name')
                                ->get();

            return response()->json([
                'success' => true,
                'categories' => $categories,
                'subProject' => [
                    'id' => $subProject->id,
                    'name' => $subProject->name,
                    'project_id' => $subProject->project_id
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Get Categories Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get materials for a sub project
     */
    public function getSubProjectMaterials($subProjectId)
    {
        try {
            // Verify sub project exists
            $subProject = SubProject::findOrFail($subProjectId);
            
            $materials = Material::with('category:id,name')
                                ->where('sub_project_id', $subProjectId)
                                ->select('id', 'name', 'unit', 'category_id', 'sub_project_id')
                                ->orderBy('name')
                                ->get();

            return response()->json([
                'success' => true,
                'materials' => $materials,
                'subProject' => [
                    'id' => $subProject->id,
                    'name' => $subProject->name,
                    'project_id' => $subProject->project_id
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete vendor
     */
    public function deleteVendor($id)
    {
        try {
            $vendor = Vendor::findOrFail($id);
            
            // Check if vendor has transactions
            if ($vendor->transactions()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor tidak dapat dihapus karena memiliki transaksi'
                ], 422);
            }

            $vendor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vendor berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus vendor'
            ], 500);
        }
    }

    /**
     * Delete project
     */
    public function deleteProject($id)
    {
        try {
            $project = Project::findOrFail($id);
            
            // Check if project has transactions
            if ($project->transactions()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyek tidak dapat dihapus karena memiliki transaksi'
                ], 422);
            }

            // Delete related sub projects and materials
            SubProject::where('project_id', $id)->delete();
            Material::where('project_id', $id)->delete();
            
            $project->delete();

            return response()->json([
                'success' => true,
                'message' => 'Proyek berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus proyek'
            ], 500);
        }
    }

    /**
     * Delete sub project
     */
    public function deleteSubProject($id)
    {
        try {
            $subProject = SubProject::findOrFail($id);
            
            // Check if sub project has transactions
            if ($subProject->transactions()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub proyek tidak dapat dihapus karena memiliki transaksi'
                ], 422);
            }

            $subProject->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sub proyek berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus sub proyek'
            ], 500);
        }
    }

    /**
     * Delete category
     */
    public function deleteCategory($id)
    {
        try {
            $category = Category::findOrFail($id);
            
            // Check if category has materials
            if ($category->materials()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak dapat dihapus karena memiliki material'
                ], 422);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kategori'
            ], 500);
        }
    }

    /**
     * Delete material
     */
    public function deleteMaterial($id)
    {
        try {
            $material = Material::findOrFail($id);
            
            // Check if material has transaction details
            if ($material->transactionDetails()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Material tidak dapat dihapus karena memiliki riwayat transaksi'
                ], 422);
            }

            $material->delete();

            return response()->json([
                'success' => true,
                'message' => 'Material berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus material'
            ], 500);
        }
    }
}
