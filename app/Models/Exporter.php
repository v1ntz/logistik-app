<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exporter extends Model
{
    protected $fillable = ['name', 'location'];

    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }
}
