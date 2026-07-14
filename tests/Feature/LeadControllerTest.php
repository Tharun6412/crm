<?php

namespace Tests\Feature;

use App\Models\Admin\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LeadControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_lead_index_page_loads()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/lms/leads');

        $response->assertStatus(200);
        $response->assertViewIs('lms.list');
    }
}
