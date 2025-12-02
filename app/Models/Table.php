<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'status',
        'is_available',
    ];

    public function statusColor(): string
    {
        return match ($this->status) {
            'available' => 'success',
            'reserved' => 'warning',
            'occupied' => 'danger',
            'cleaning' => 'info',
            default => 'secondary',
        };
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function scopeAvailableForReservation($query, $date, $time, $guestCount)
    {
        return $query->where('is_available', true)
            ->where('status', 'available')
            ->where('capacity', '>=', $guestCount)
            ->whereDoesntHave('reservations', function ($q) use ($date, $time) {
                $q->where('reservation_date', $date)
                    ->where('reservation_time', $time)
                    ->whereIn('status', ['pending', 'confirmed']);
            });
    }
}
