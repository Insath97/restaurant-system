<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reviewable_type',
        'reviewable_id',
        'review_title',
        'comment',
        'rating',
        'food_quality',
        'service_quality',
        'ambiance',
        'would_recommend',
        'is_approved',
        'is_featured'
    ];

    protected $casts = [
        'would_recommend' => 'boolean',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
        'rating' => 'integer',
        'food_quality' => 'integer',
        'service_quality' => 'integer',
        'ambiance' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewable()
    {
        return $this->morphTo();
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function approve()
    {
        return $this->update(['is_approved' => true]);
    }

    public function reject(): bool
    {
        return $this->update(['is_approved' => false]);
    }

    public function getReviewTypeAttribute(): string
    {
        if (!$this->reviewable_type) {
            return 'Unknown';
        }

        return match ($this->reviewable_type) {
            'App\Models\Order' => 'Order',
            'App\Models\Reservation' => 'Reservation',
            default => 'Unknown',
        };
    }

    public function getReviewableReferenceAttribute(): string
    {
        // Don't try to access reviewable if it's not loaded
        if (!$this->relationLoaded('reviewable') || !$this->reviewable) {
            return 'N/A';
        }

        if ($this->reviewable_type === 'App\Models\Order') {
            return $this->reviewable->order_number ?? 'N/A';
        } elseif ($this->reviewable_type === 'App\Models\Reservation') {
            return $this->reviewable->code ?? 'N/A';
        }

        return 'N/A';
    }

    public function getStarRatingAttribute(): string
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-warning"></i>';
            }
        }
        return $stars;
    }
}
