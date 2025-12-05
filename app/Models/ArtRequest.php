<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\User;

class ArtRequest extends Model
{
    protected $guarded = [];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function designer()
    {
        return $this->belongsTo(User::class, 'designer_id');
    }
}
