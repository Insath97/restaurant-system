<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'reservation_id',
        'order_type',
        'status',
        'subtotal',
        'service_charge',
        'delivery_fee',
        'total',
        'customer_name',
        'customer_email',
        'customer_phone',
        'delivery_address',
        'special_instructions',
        'payment_method',
        'payment_status',
        'order_date'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'payment_status' => 'boolean',
        'order_date' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'preparing' => 'primary',
            'ready' => 'success',
            'completed' => 'secondary',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function statusText(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'preparing' => 'Preparing',
            'ready' => 'Ready for Pickup',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => 'Unknown',
        };
    }

    public function paymentMethodText(): string
    {
        return match ($this->payment_method) {
            'cash_on_delivery' => 'Cash on Delivery',
            'credit_card' => 'Credit Card',
            'paypal' => 'PayPal',
            default => 'Unknown',
        };
    }

    public static function generateOrderNumber(): string
    {
        $prefix = 'DFR';
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', today())->count();

        return $prefix . '-' . $date . '-' . str_pad($lastOrder + 1, 4, '0', STR_PAD_LEFT);
    }
}
