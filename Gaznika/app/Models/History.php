<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'description',
        'changes',
        'amount',
        'delivery_type',
        'status',
        'priority',
        'admin_name',
        'assigned_to',
    ];

    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}