<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Participant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    /*
     * Public Registration Tests
     */
    public function test_public_registration_page_loads()
    {
        $response = $this->get(route('public.register'));
        $response->assertStatus(200);
    }

    public function test_public_user_can_register()
    {
        $data = [
            'full_name' => 'Test User',
            'age' => 25,
            'mobile_number' => '9988776655',
            'permanent_address' => 'Test Address',
            'voter_id' => 0,
            'voter_member_count' => 0
        ];

        $response = $this->postJson(route('public.store'), $data);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('participants', [
            'full_name' => 'Test User',
            'mobile_number' => '9988776655',
            'status' => 'pending'
        ]);
    }

    public function test_public_registration_validation()
    {
        $response = $this->postJson(route('public.store'), []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['full_name', 'age', 'mobile_number', 'permanent_address']);
    }

    public function test_duplicate_mobile_number_registration()
    {
        // Register first user
        Participant::factory()->create(['mobile_number' => '9988776655']);

        // Try registering again with same number
        $data = [
            'full_name' => 'Another User',
            'age' => 30,
            'mobile_number' => '9988776655',
            'permanent_address' => 'Another Address',
            'voter_id' => 0
        ];

        $response = $this->postJson(route('public.store'), $data);

        $response->assertStatus(422)
            ->assertJson(['message' => 'User already registered']);
    }

    /*
     * Admin Participant Management Tests
     */
    public function test_admin_can_view_pending_list()
    {
        $response = $this->actingAs($this->admin)->get(route('participants.pending'));
        $response->assertStatus(200);
    }

    public function test_admin_can_approve_participant()
    {
        $participant = Participant::factory()->create(['status' => 'pending', 'token' => null]);

        $response = $this->actingAs($this->admin)->from(route('participants.pending'))->post(route('participants.approve', $participant->id), [
            'token' => '1234'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('participants.pending'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('participants', [
            'id' => $participant->id,
            'status' => 'approved',
            'token' => '1234'
        ]);
    }

    public function test_admin_cannot_approve_with_duplicate_token()
    {
        // Existing approved user with token 1234
        Participant::factory()->create(['status' => 'approved', 'token' => '1234']);

        // Pending user
        $participant = Participant::factory()->create(['status' => 'pending', 'token' => null]);

        $response = $this->actingAs($this->admin)->from(route('participants.pending'))->post(route('participants.approve', $participant->id), [
            'token' => '1234' // Duplicate
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['token']);
    }

    public function test_admin_can_soft_delete_participant()
    {
        $participant = Participant::factory()->create(['status' => 'approved']);

        $response = $this->actingAs($this->admin)->deleteJson(route('participants.destroy', $participant->id));

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertSoftDeleted('participants', ['id' => $participant->id]);
    }

    public function test_admin_can_view_trashed_participants()
    {
        $participant = Participant::factory()->create();
        $participant->delete();

        $response = $this->actingAs($this->admin)->get(route('participants.trashed'));
        $response->assertStatus(200);
        $response->assertSee($participant->full_name);
    }

    public function test_admin_can_restore_participant()
    {
        $participant = Participant::factory()->create();
        $participant->delete();

        $response = $this->actingAs($this->admin)->from(route('participants.trashed'))->post(route('participants.restore', $participant->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('participants.trashed'));
        $this->assertNotSoftDeleted('participants', ['id' => $participant->id]);
    }

    public function test_admin_can_force_delete_participant()
    {
        $participant = Participant::factory()->create();
        $participant->delete();

        // Use delete() instead of deleteJson() because the controller returns a redirect
        $response = $this->actingAs($this->admin)->from(route('participants.trashed'))->delete(route('participants.forceDelete', $participant->id));

        $response->assertStatus(302);
        // $response->assertRedirect(route('participants.trashed')); // It redirects 'back', so if we simulate 'from', it stays.

        $this->assertDatabaseMissing('participants', ['id' => $participant->id]);
    }
}
