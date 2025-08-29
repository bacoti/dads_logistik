<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MasterDataApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create([
            'role' => 'admin'
        ]);
    }

    public function test_init_endpoint_returns_master_data()
    {
        $this->actingAs($this->user);

        // Create test data
        Vendor::create(['name' => 'Test Vendor']);
        Project::create(['name' => 'Test Project', 'code' => 'TP001']);
        Category::create(['name' => 'Test Category']);

        $response = $this->getJson('/api/master-data/init');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true
                ])
                ->assertJsonStructure([
                    'success',
                    'vendors',
                    'projects',
                    'categories'
                ]);
    }

    public function test_store_vendor_creates_new_vendor()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/vendors', [
            'name' => 'New Test Vendor'
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Vendor berhasil ditambahkan'
                ]);

        $this->assertDatabaseHas('vendors', [
            'name' => 'New Test Vendor'
        ]);
    }

    public function test_store_project_creates_new_project()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/projects', [
            'name' => 'New Test Project'
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Proyek berhasil ditambahkan'
                ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'New Test Project'
        ]);
    }
}
