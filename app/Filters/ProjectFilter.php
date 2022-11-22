<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class ProjectFilter extends QueryFilter
{

    public function baseRequest(): Builder
    {
        return $this->builder
            ->select('projects.*')
            ->join('project_user', 'id', '=', 'project_user.project_id')
            ->join('users', 'users.id', '=', 'project_user.user_id')
            ->where(function ($query) {
                $query->orWhere('projects.author_id', $this->request->user()->id);
                $query->orWhere('users.id', $this->request->user()->id);
            });
    }

    /**
     * @param string $email
     * @return Builder
     */
    public function user_email(string $email): Builder
    {
        return $this->baseRequest()
            ->where('users.email', $email);
    }

    /**
     * @param string $continent
     * @return Builder
     */
    public function user_continent(string $continent): Builder
    {
        return $this->baseRequest()
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
        return $this->baseRequest()
            ->join('label_project', 'projects.id', '=', 'label_project.project_id')
            ->join('labels', 'labels.id', '=', 'label_project.label_id')
            ->where('labels.name', $label);
    }
}
