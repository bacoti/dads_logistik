<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\BOQActual;
use App\Models\Material;
use App\Models\Project;
use App\Models\SubProject;
use App\Models\Transaction;
use App\Models\TransactionDetail;

class BoqPemakaianFlowTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // Create minimal master data
    $this->project = Project::create([ 'name' => 'Test Project', 'code' => 'TP-001' ]);
        $this->subProject = SubProject::create([ 'project_id' => $this->project->id, 'name' => 'Test Sub' ]);
    $this->category = \App\Models\Category::create(['name' => 'Test Cat']);
    $this->material = Material::create([ 'sub_project_id' => $this->subProject->id, 'category_id' => $this->category->id, 'name' => 'Test Material', 'unit' => 'pcs' ]);
        $this->user = \App\Models\User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_prefill_and_post_creates_transaction_and_updates_boq()
    {
        // Create a BOQActual with quantity 10
        $boq = BOQActual::create([
            'user_id' => $this->user->id,
            'project_id' => $this->project->id,
            'sub_project_id' => $this->subProject->id,
            'material_id' => $this->material->id,
            'cluster' => 'C1',
            'dn_number' => 'DN-1',
            'actual_quantity' => 10,
            'usage_date' => now()->toDateString(),
            'notes' => 'Test BOQ'
        ]);

        // Prefill endpoint should return JSON with remaining_quantity = 10
        $resp = $this->getJson(route('admin.boq-actuals.create-pemakaian.prefill', $boq->id));
        $resp->assertStatus(200)->assertJsonFragment(['remaining_quantity' => 10]);

        // Post pemakaian of quantity 4
        $postResp = $this->post(route('admin.boq-actuals.create-pemakaian', $boq->id), [
            'post' => 1,
            'quantity' => 4
        ]);

        // Should redirect to transactions.show or return JSON success
        $postResp->assertStatus(302)->or($postResp->assertJson(['ok' => true]));

        // Verify a transaction + detail created
        $this->assertDatabaseHas('transactions', ['type' => 'pemakaian', 'project_id' => $this->project->id]);
        $this->assertDatabaseHas('transaction_details', ['material_id' => $this->material->id, 'quantity' => 4]);

        // Refresh BOQ and check posted_quantity updated
        $boq->refresh();
        $this->assertEquals(4, (float)$boq->posted_quantity);
    }

    public function test_exports_endpoints_respond()
    {
        // CSV export
        $csv = $this->get(route('boq-actuals.unposted.export'));
        $csv->assertStatus(200);

        // XLSX export
        $xlsx = $this->get(route('boq-actuals.unposted.export.xlsx'));
        $xlsx->assertStatus(200);
    }
}
