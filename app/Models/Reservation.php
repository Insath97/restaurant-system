<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'table_id',
        'code',
        'reservation_date',
        'reservation_time',
        'guest_count',
        'special_requests',
        'status'
    ];

    protected $casts = [
        'reservation_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'confirmed' => 'success',
            'pending' => 'warning',
            'cancelled' => 'danger',
            'completed' => 'info',
            default => 'secondary',
        };
    }

    public static function generateReservationCode(): string
    {
        $prefix = 'RES';
        $date = now()->format('Ymd');
        $lastReservation = self::whereDate('created_at', today())->count();

        return $prefix . '-' . $date . '-' . str_pad($lastReservation + 1, 4, '0', STR_PAD_LEFT);
    }

    public function scopeAvailableTables($query, $date, $time, $guestCount)
    {
        return Table::where('is_available', true)
            ->where('capacity', '>=', $guestCount)
            ->whereDoesntHave('reservations', function ($q) use ($date, $time) {
                $q->where('reservation_date', $date)
                    ->where('reservation_time', $time)
                    ->whereIn('status', ['pending', 'confirmed']);
            });
    }
}
