<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_name',
        'booking_date',
        'booking_time',
        'notes',
        'status',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
}
