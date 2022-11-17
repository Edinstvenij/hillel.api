<?php

namespace App\Models;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'author_id'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }
}
