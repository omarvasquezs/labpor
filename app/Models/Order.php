<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'code',
        'client_name',
        'type',
        'quantity',
        'status',
        'due_date',
        'rejection_reason',
    ];

    public function artRequests()
    {
        return $this->hasMany(ArtRequest::class);
    }

    public function productionStages()
    {
        return $this->hasMany(ProductionStage::class);
    }
}
