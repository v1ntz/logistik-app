<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logbook extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'status', 'headcount', 'gross_weight', 'tare_weight', 'net_weight',
        'driver_name', 'license_plate', 'route_id', 'pic_name',
        'cattle_type_id', 'supplier_id', 'additional_costs', 'additional_costs_notes'
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function cattleType()
    {
        return $this->belongsTo(CattleType::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
