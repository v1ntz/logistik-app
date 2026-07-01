<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kapal extends Model
{
    protected $fillable = ['nama_kapal', 'eta'];

    public function manifests()
    {
        return $this->hasMany(KapalManifest::class);
    }
}
