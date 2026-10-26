<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Company;
use App\Models\CompanyService;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Users
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'phone' => '+1 555-0100',
                'address' => '123 Admin Ave, Suite 100',
            ]
        );

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => 'client',
                'email_verified_at' => now(),
                'phone' => '+1 555-0101',
                'address' => '456 Client St, Apt 2B',
            ]
        );

        // Companies
        $glowSpa = Company::updateOrCreate(
            ['slug' => 'glow-spa'],
            [
                'name' => 'Glow Spa & Wellness',
                'email' => 'hello@glowspa.com',
                'phone' => '+1 555-1001',
                'address' => '789 Wellness Way',
                'city' => 'New York',
                'website' => 'https://glowspa.com',
                'description' => 'Premium spa and wellness center offering relaxing treatments.',
                'is_active' => true,
            ]
        );

        $cityDental = Company::updateOrCreate(
            ['slug' => 'city-dental'],
            [
                'name' => 'City Dental Care',
                'email' => 'info@citydental.com',
                'phone' => '+1 555-2001',
                'address' => '321 Smile Boulevard',
                'city' => 'New York',
                'website' => 'https://citydental.com',
                'description' => 'Modern dental clinic with experienced dentists.',
                'is_active' => true,
            ]
        );

        $eliteBarbers = Company::updateOrCreate(
            ['slug' => 'elite-barbers'],
            [
                'name' => 'Elite Barbershop',
                'email' => 'bookings@elitebarbers.com',
                'phone' => '+1 555-3001',
                'address' => '565 Cut & Style Lane',
                'city' => 'New York',
                'website' => 'https://elitebarbers.com',
                'description' => "Classic barbershop for gentlemen's grooming.",
                'is_active' => true,
            ]
        );

        $fitnessHub = Company::updateOrCreate(
            ['slug' => 'fitness-hub'],
            [
                'name' => 'Fitness Hub Personal Training',
                'email' => 'train@fitnesshub.com',
                'phone' => '+1 555-4001',
                'address' => '777 Muscle Road',
                'city' => 'New York',
                'website' => 'https://fitnesshub.com',
                'description' => 'Personal training and fitness coaching.',
                'is_active' => true,
            ]
        );

        $consultPro = Company::updateOrCreate(
            ['slug' => 'consult-pro'],
            [
                'name' => 'ConsultPro Business Advisors',
                'email' => 'contact@consultpro.com',
                'phone' => '+1 555-5001',
                'address' => '888 Executive Tower, Floor 10',
                'city' => 'New York',
                'website' => 'https://consultpro.com',
                'description' => 'Professional business consulting services.',
                'is_active' => true,
            ]
        );

        // Services
        $haircut = Service::updateOrCreate(
            ['slug' => 'haircut'],
            [
                'name' => 'Haircut',
                'category' => 'Beauty',
                'description' => 'Professional haircut and styling.',
                'duration_minutes' => 30,
                'is_active' => true,
            ]
        );

        $massage = Service::updateOrCreate(
            ['slug' => 'massage'],
            [
                'name' => 'Relaxation Massage',
                'category' => 'Beauty',
                'description' => 'Full body relaxation massage.',
                'duration_minutes' => 60,
                'is_active' => true,
            ]
        );

        $facial = Service::updateOrCreate(
            ['slug' => 'facial'],
            [
                'name' => 'Deep Cleanse Facial',
                'category' => 'Beauty',
                'description' => 'Deep cleansing facial treatment.',
                'duration_minutes' => 45,
                'is_active' => true,
            ]
        );

        $cleaning = Service::updateOrCreate(
            ['slug' => 'teeth-cleaning'],
            [
                'name' => 'Teeth Cleaning',
                'category' => 'Dental',
                'description' => 'Professional teeth cleaning and polishing.',
                'duration_minutes' => 45,
                'is_active' => true,
            ]
        );

        $checkup = Service::updateOrCreate(
            ['slug' => 'dental-checkup'],
            [
                'name' => 'Dental Checkup',
                'category' => 'Dental',
                'description' => 'Routine dental examination.',
                'duration_minutes' => 30,
                'is_active' => true,
            ]
        );

        $personalTraining = Service::updateOrCreate(
            ['slug' => 'personal-training'],
            [
                'name' => 'Personal Training Session',
                'category' => 'Fitness',
                'description' => 'One-on-one personal training session.',
                'duration_minutes' => 60,
                'is_active' => true,
            ]
        );

        $businessConsulting = Service::updateOrCreate(
            ['slug' => 'business-consulting'],
            [
                'name' => 'Business Consulting',
                'category' => 'Consulting',
                'description' => 'Strategic business consulting session.',
                'duration_minutes' => 60,
                'is_active' => true,
            ]
        );

        // Company Services (with prices)
        // Glow Spa services
        CompanyService::updateOrCreate(
            ['company_id' => $glowSpa->id, 'service_id' => $massage->id],
            ['price' => 85.00, 'duration_minutes' => 60, 'is_available' => true]
        );
        CompanyService::updateOrCreate(
            ['company_id' => $glowSpa->id, 'service_id' => $facial->id],
            ['price' => 65.00, 'duration_minutes' => 45, 'is_available' => true]
        );

        // City Dental services
        CompanyService::updateOrCreate(
            ['company_id' => $cityDental->id, 'service_id' => $cleaning->id],
            ['price' => 120.00, 'duration_minutes' => 45, 'is_available' => true]
        );
        CompanyService::updateOrCreate(
            ['company_id' => $cityDental->id, 'service_id' => $checkup->id],
            ['price' => 80.00, 'duration_minutes' => 30, 'is_available' => true]
        );

        // Elite Barbers services
        CompanyService::updateOrCreate(
            ['company_id' => $eliteBarbers->id, 'service_id' => $haircut->id],
            ['price' => 35.00, 'duration_minutes' => 30, 'is_available' => true]
        );

        // Fitness Hub services
        CompanyService::updateOrCreate(
            ['company_id' => $fitnessHub->id, 'service_id' => $personalTraining->id],
            ['price' => 75.00, 'duration_minutes' => 60, 'is_available' => true]
        );

        // ConsultPro services
        CompanyService::updateOrCreate(
            ['company_id' => $consultPro->id, 'service_id' => $businessConsulting->id],
            ['price' => 150.00, 'duration_minutes' => 60, 'is_available' => true]
        );

        // Sample bookings
        Booking::updateOrCreate(
            ['id' => 1],
            [
                'user_id' => User::where('email', 'test@example.com')->first()->id,
                'company_service_id' => CompanyService::where('company_id', $glowSpa->id)->where('service_id', $massage->id)->first()->id,
                'service_name' => 'Relaxation Massage at Glow Spa & Wellness',
                'price_paid' => 85.00,
                'booking_date' => now()->addDay()->toDateString(),
                'booking_time' => '10:00:00',
                'notes' => 'Please use lavender oil.',
                'status' => 'pending',
            ]
        );

        Booking::updateOrCreate(
            ['id' => 2],
            [
                'user_id' => User::where('email', 'test@example.com')->first()->id,
                'company_service_id' => CompanyService::where('company_id', $cityDental->id)->where('service_id', $cleaning->id)->first()->id,
                'service_name' => 'Teeth Cleaning at City Dental Care',
                'price_paid' => 120.00,
                'booking_date' => now()->addDays(2)->toDateString(),
                'booking_time' => '14:00:00',
                'notes' => null,
                'status' => 'confirmed',
            ]
        );
    }
}
