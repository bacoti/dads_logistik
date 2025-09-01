<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\SubProject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class UserLapanganTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user lapangan
        $this->user = User::factory()->create([
            'name' => 'Test User Lapangan',
            'email' => 'test@lapangan.com',
            'role' => 'user',
            'password' => bcrypt('password')
        ]);
    }

    /** @test */
    public function user_lapangan_can_access_dashboard()
    {
        $response = $this->actingAs($this->user)
                        ->get('/user/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Selamat datang');
    }

    /** @test */
    public function user_lapangan_can_access_transaction_index()
    {
        $response = $this->actingAs($this->user)
                        ->get('/user/transactions');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_lapangan_can_access_create_transaction_form()
    {
        $response = $this->actingAs($this->user)
                        ->get('/user/transactions/create?type=penerimaan');

        $response->assertStatus(200);
        $response->assertSee('Form Penerimaan Material');
    }

    /** @test */
    public function user_lapangan_cannot_access_admin_routes()
    {
        $response = $this->actingAs($this->user)
                        ->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    /** @test */
    public function user_lapangan_cannot_access_po_routes()
    {
        $response = $this->actingAs($this->user)
                        ->get('/po/dashboard');

        $response->assertStatus(403);
    }

    /** @test */
    public function health_check_endpoint_works()
    {
        $response = $this->get('/health-check');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'timestamp',
            'checks'
        ]);
    }

    /** @test */
    public function status_endpoint_works()
    {
        $response = $this->get('/status');
        
        $response->assertStatus(200);
        $response->assertSee('OK');
    }

    /** @test */
    public function user_can_create_transaction_with_valid_data()
    {
        // Create required master data
        $project = Project::factory()->create();
        $subProject = SubProject::factory()->create(['project_id' => $project->id]);
        
        // Create material with proper category relationship
        $category = \App\Models\Category::factory()->create(['sub_project_id' => $subProject->id]);
        $material = \App\Models\Material::factory()->create([
            'sub_project_id' => $subProject->id,
            'category_id' => $category->id
        ]);

        $transactionData = [
            'type' => 'penerimaan',
            'transaction_date' => now()->format('Y-m-d'),
            'vendor_name' => 'Test Vendor',
            'project_id' => $project->id,
            'sub_project_id' => $subProject->id,
            'location' => 'Test Location',
            'materials' => [
                ['material_id' => $material->id, 'quantity' => 10]
            ]
        ];

        $response = $this->actingAs($this->user)
                        ->post('/user/transactions', $transactionData);

        // Should redirect on success
        $response->assertRedirect('/user/dashboard');
        
        // Verify transaction was created
        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'type' => 'penerimaan',
            'vendor_name' => 'Test Vendor',
            'location' => 'Test Location'
        ]);
    }
}
