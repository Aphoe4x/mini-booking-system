<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_service_id',
        'service_name',
        'price_paid',
        'booking_date',
        'booking_time',
        'notes',
        'status',
    ];

    protected $casts = [
        'booking_date' => 'date:Y-m-d',
        'booking_time' => 'datetime:H:i',
        'price_paid' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function companyService(): BelongsTo
    {
        return $this->belongsTo(CompanyService::class);
    }

    // Scope: only bookings belonging to a given user
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    // Scope: only bookings still awaiting admin action
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    // Scope: upcoming bookings (today or later), soonest first
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('booking_date', '>=', now()->toDateString())
            ->orderBy('booking_date')
            ->orderBy('booking_time');
    }

    // Scope: past bookings (before today), soonest first
    public function scopePast(Builder $query): Builder
    {
        return $query->where('booking_date', '<', now()->toDateString())
            ->orderByDesc('booking_date')
            ->orderByDesc('booking_time');
    }
}
