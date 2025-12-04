<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function artRequests()
    {
        return $this->hasMany(ArtRequest::class);
    }

    public function productionStages()
    {
        return $this->hasMany(ProductionStage::class);
    }
}
