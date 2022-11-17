<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class ProjectFilter extends QueryFilter
{
    /**
     * @param string $email
     * @return Builder
     */
    public function user_email(string $email): Builder
    {
        return $this->builder
            ->join('project_user', 'id', '=', 'project_user.project_id')
            ->join('users', 'users.id', '=', 'project_user.user_id')
            ->where('users.email', $email);
    }

    /**
     * @param string $continent
     * @return Builder
     */
    public function user_continent(string $continent): Builder
    {
        return $this->builder
            ->join('project_user', 'id', '=', 'project_user.project_id')
            ->join('users', 'users.id', '=', 'project_user.user_id')
            ->join('countries', 'countries.id', '=', 'users.country_id')
            ->join('continents', 'continents.id', '=', 'countries.continent_id')
            ->where('continents.code', $continent);
    }

    /**
     * @param string $label
     * @return Builder
     */
    public function labels(string $label): Builder
    {
        return $this->builder
            ->join('label_user', 'id', '=', 'label_user.project_id')
            ->join('labels', 'labels.id', '=', 'label_user.label_id')
            ->where('labels.name', $label);
    }
}
