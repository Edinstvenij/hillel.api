<?php

namespace App\Models;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'country_id',
        'token'
    ];

    public function countries()
    {
        return $this->hasMany(Country::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class);
    }

    public function authorProjects()
    {
        return $this->belongsToMany(Label::class);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }
}
