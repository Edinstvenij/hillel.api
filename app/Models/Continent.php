<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Continent extends Model
{
    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        'code'
    ];

    public function countries()
    {
        return $this->hasMany(Country::class);
    }
}
