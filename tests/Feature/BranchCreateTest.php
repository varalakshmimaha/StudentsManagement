<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BranchCreateTest extends TestCase
{
    // use RefreshDatabase;

    public function test_can_create_branch()
    {
        $user = User::first(); // Grab first user assuming admin
        if (!$user) {
            $user = User::factory()->create();
        }
        
        $response = $this->actingAs($user)
                         ->post(route('branches.store'), [
            'name' => 'Test Branch',
            'code' => 'TB001',
            'address' => 'Test Address',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('branches.index'));
        $this->assertDatabaseHas('branches', ['code' => 'TB001']);
    }
}
