<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Company;
use App\Models\CompanyService;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MarketplaceBookingTest extends TestCase
{
    use RefreshDatabase;

    private function client(): User
    {
        return User::factory()->create([
            'role' => 'client',
            'email_verified_at' => now(),
        ]);
    }

    private function companyService(): CompanyService
    {
        $company = Company::create([
            'name' => 'Glow Spa & Wellness',
            'slug' => 'glow-spa',
            'city' => 'New York',
            'is_active' => true,
        ]);

        $service = Service::create([
            'name' => 'Relaxation Massage',
            'slug' => 'massage',
            'category' => 'Beauty',
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        return CompanyService::create([
            'company_id' => $company->id,
            'service_id' => $service->id,
            'price' => 85,
            'duration_minutes' => 60,
            'is_available' => true,
        ]);
    }

    private function nextWeekday(): Carbon
    {
        $date = now()->addDay();

        while ($date->isWeekend()) {
            $date->addDay();
        }

        return $date;
    }

    public function test_companies_can_be_browsed_and_viewed(): void
    {
        $this->companyService();
        $user = $this->client();

        $this->actingAs($user)
            ->get('/companies')
            ->assertOk()
            ->assertSee('Glow Spa & Wellness')
            ->assertSee('From $85');

        $this->actingAs($user)
            ->get('/companies/'.Company::first()->id)
            ->assertOk()
            ->assertSee('Relaxation Massage')
            ->assertSee('$85.00');
    }

    public function test_booking_uses_the_server_side_price_and_company_label(): void
    {
        Mail::fake();

        $companyService = $this->companyService();
        $user = $this->client();
        $date = $this->nextWeekday();

        $this->actingAs($user)
            ->post('/bookings', [
                'company_id' => $companyService->company_id,
                'service_id' => $companyService->service_id,
                'company_service_id' => $companyService->id,
                'price_paid' => 1, // tampered price must be ignored
                'booking_date' => $date->toDateString(),
                'booking_time' => '10:00',
                'notes' => 'Please use lavender oil.',
            ])
            ->assertRedirect(route('bookings.index'));

        $booking = Booking::first();

        $this->assertNotNull($booking);
        $this->assertSame('85.00', $booking->price_paid);
        $this->assertSame('Relaxation Massage at Glow Spa & Wellness', $booking->service_name);
        $this->assertSame('pending', $booking->status);
        $this->assertSame($date->toDateString(), $booking->booking_date->toDateString());
    }

    public function test_duplicate_slot_is_rejected(): void
    {
        Mail::fake();

        $companyService = $this->companyService();
        $user = $this->client();
        $date = $this->nextWeekday();

        $payload = [
            'company_service_id' => $companyService->id,
            'booking_date' => $date->toDateString(),
            'booking_time' => '10:00',
        ];

        $this->actingAs($user)->post('/bookings', $payload)->assertRedirect(route('bookings.index'));

        $this->actingAs($user)
            ->from('/bookings/create')
            ->post('/bookings', $payload)
            ->assertRedirect('/bookings/create')
            ->assertSessionHasErrors('booking_time');

        $this->assertSame(1, Booking::count());
    }

    public function test_weekend_bookings_are_rejected(): void
    {
        Mail::fake();

        $companyService = $this->companyService();
        $user = $this->client();

        $this->actingAs($user)
            ->from('/bookings/create')
            ->post('/bookings', [
                'company_service_id' => $companyService->id,
                'booking_date' => now()->next('Saturday')->toDateString(),
                'booking_time' => '10:00',
            ])
            ->assertRedirect('/bookings/create')
            ->assertSessionHasErrors('booking_date');

        $this->assertSame(0, Booking::count());
    }
}
