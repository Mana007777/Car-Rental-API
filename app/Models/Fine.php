<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $fillable = [
        'rental_id',
        'amount',
        'reason',
        'paid',
        'fine_date',
    ];

    protected $casts = [
        'paid' => 'boolean',
        'fine_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}