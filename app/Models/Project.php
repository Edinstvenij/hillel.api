<?php

namespace App\Models;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'author_id'
    ];

    public function author()
    {
        return $this->hasMany(User::class);
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }
}
