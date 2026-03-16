<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'table_name',
        'record_id',
        'description',
        'old_values',
        'new_values',
        'action_date',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'action_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}