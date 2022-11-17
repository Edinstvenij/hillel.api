<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'continent_id'
    ];

    public function continents()
    {
        return $this->hasMany(Continent::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

}
