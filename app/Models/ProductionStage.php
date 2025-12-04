<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\ProductionLog;

class ProductionStage extends Model
{
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productionLogs()
    {
        return $this->hasMany(ProductionLog::class);
    }
}
