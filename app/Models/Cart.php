<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subtotal',
        'service_charge',
        'total'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function calculateTotals(): void
    {
        $this->subtotal = $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $this->service_charge = $this->subtotal * 0.1;
        $this->total = $this->subtotal + $this->service_charge;
        $this->save();
    }
}
