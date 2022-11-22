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

    public function country()
    {
        return $this->belongsTo(Country::class)->first();
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function authorLabels()
    {
        return $this->hasMany(Label::class, 'author_id', 'id');
    }

    public function authorProjects()
    {
        return $this->hasMany(Project::class, 'author_id', 'id');
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }
}
