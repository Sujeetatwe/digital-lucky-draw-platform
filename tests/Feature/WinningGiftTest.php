<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WinningGift;
use App\Models\Participant;
use App\Models\Winner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WinningGiftTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create an admin user for authentication
        $this->user = User::factory()->create();
    }

    /*
     * Part 1: Winning Gifts Master (Admin) Tests
     */
    public function test_can_view_winning_gifts_page()
    {
        $response = $this->actingAs($this->user)->get(route('winning-gifts.index'));
        $response->assertStatus(200);
    }

    public function test_can_create_winning_gift()
    {
        $response = $this->actingAs($this->user)->post(route('winning-gifts.store'), [
            'gift_name' => 'Test Gift',
            'quantity' => 5
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('winning_gifts', ['gift_name' => 'Test Gift', 'quantity' => 5]);
    }

    public function test_can_update_winning_gift()
    {
        $gift = WinningGift::create(['gift_name' => 'Old Name', 'quantity' => 1]);

        $response = $this->actingAs($this->user)->put(route('winning-gifts.update', $gift), [
            'gift_name' => 'New Name',
            'quantity' => 10
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('winning_gifts', ['id' => $gift->id, 'gift_name' => 'New Name', 'quantity' => 10]);
    }

    public function test_can_delete_winning_gift()
    {
        $gift = WinningGift::create(['gift_name' => 'To Delete', 'quantity' => 1]);

        $response = $this->actingAs($this->user)->delete(route('winning-gifts.destroy', $gift));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('winning_gifts', ['id' => $gift->id]);
    }

    /*
     * Part 2: Winner Assignment Module Tests
     */
    public function test_search_api_returns_participants()
    {
        $participant = Participant::factory()->create([
            'status' => 'approved',
            'full_name' => 'John Doe',
            'token' => '1234',
            'mobile_number' => '9876543210'
        ]);

        $response = $this->actingAs($this->user)->get(route('winners.search', ['query' => 'John']));

        $response->assertStatus(200)
            ->assertJsonFragment(['full_name' => 'John Doe']);
    }

    public function test_cannot_assign_gift_if_already_winner()
    {
        $gift = WinningGift::create(['gift_name' => 'Bike', 'quantity' => 1]);
        $participant = Participant::factory()->create(['status' => 'approved']);

        // Winning once
        Winner::create([
            'participant_id' => $participant->id,
            'winning_gift_id' => $gift->id,
            'quantity' => 1,
            'assigned_by' => $this->user->id
        ]);

        // Try assigning again
        $response = $this->actingAs($this->user)->postJson(route('winners.assign'), [
            'participant_id' => $participant->id,
            'winning_gift_id' => $gift->id
        ]);

        $response->assertStatus(422)
            ->assertJson(['message' => 'This participant has already won a gift.']);
    }

    public function test_can_assign_gift_successfully()
    {
        $gift = WinningGift::create(['gift_name' => 'Car', 'quantity' => 1]);
        $participant = Participant::factory()->create(['status' => 'approved']);

        $response = $this->actingAs($this->user)->postJson(route('winners.assign'), [
            'participant_id' => $participant->id,
            'winning_gift_id' => $gift->id
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Gift assigned successfully.']);

        $this->assertDatabaseHas('winners', [
            'participant_id' => $participant->id,
            'winning_gift_id' => $gift->id
        ]);
    }

    public function test_gift_quantity_decrements_after_assignment()
    {
        $gift = WinningGift::create(['gift_name' => 'TV', 'quantity' => 5]);
        $participant = Participant::factory()->create(['status' => 'approved']);

        $response = $this->actingAs($this->user)->postJson(route('winners.assign'), [
            'participant_id' => $participant->id,
            'winning_gift_id' => $gift->id
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('winning_gifts', [
            'id' => $gift->id,
            'quantity' => 4
        ]);
    }

    public function test_cannot_assign_gift_if_out_of_stock()
    {
        $gift = WinningGift::create(['gift_name' => 'Empty Gift', 'quantity' => 0]);
        $participant = Participant::factory()->create(['status' => 'approved']);

        $response = $this->actingAs($this->user)->postJson(route('winners.assign'), [
            'participant_id' => $participant->id,
            'winning_gift_id' => $gift->id
        ]);

        $response->assertStatus(422)
            ->assertJson(['message' => 'This gift is out of stock.']);

        $this->assertDatabaseMissing('winners', [
            'participant_id' => $participant->id,
            'winning_gift_id' => $gift->id
        ]);
    }

    /*
     * Part 3: Winners List & Export Tests
     */
    public function test_can_fetch_winners_list_json()
    {
        $gift = WinningGift::create(['gift_name' => 'Laptop', 'quantity' => 1]);
        $participant = Participant::factory()->create(['status' => 'approved']);
        Winner::create([
            'participant_id' => $participant->id,
            'winning_gift_id' => $gift->id,
            'quantity' => 1,
            'assigned_by' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->get(route('winners.index'));

        $response->assertStatus(200)
            ->assertJsonFragment(['gift_name' => 'Laptop']);
    }

    public function test_export_winners_list()
    {
        $response = $this->actingAs($this->user)->get(route('winners.export'));

        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('Content-Type'), 'text/csv'));
        $this->assertTrue(str_contains($response->headers->get('Content-Disposition'), 'attachment; filename=winners_list.csv'));
    }
}
