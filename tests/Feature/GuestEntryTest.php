<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestEntryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_landing_on_the_root_url_are_sent_to_registration(): void
    {
        $this->get('/')->assertRedirect(route('register'));
    }

    public function test_signed_in_users_landing_on_the_root_url_are_sent_to_their_bookings(): void
    {
        $user = User::factory()->create(['role' => 'client']);

        $this->actingAs($user)->get('/')->assertRedirect('/bookings');
    }

    public function test_login_page_offers_a_link_to_create_an_account(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee(route('register'), false)
            ->assertSee('Create an account');
    }
}
