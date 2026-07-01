<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KapalManifest extends Model
{
    protected $fillable = ['kapal_id', 'importir_id', 'exporter_id', 'kade', 'consignee', 'party'];

    public function kapal()
    {
        return $this->belongsTo(Kapal::class);
    }

    public function importir()
    {
        return $this->belongsTo(Supplier::class, 'importir_id');
    }

    public function exporter()
    {
        return $this->belongsTo(Exporter::class);
    }

    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }

    // Helper: sudah berapa ekor yang dimuat dari total party
    public function totalMuat()
    {
        return $this->logbooks()->sum('headcount');
    }
}
