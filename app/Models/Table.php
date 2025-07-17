<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory;

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
}
