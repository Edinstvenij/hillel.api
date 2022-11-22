<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class LabelFilter extends QueryFilter
{

    public function baseRequest(): Builder
    {
        return $this->builder
            ->select('labels.*')
            ->distinct()
            ->join('label_project', 'labels.id', '=', 'label_project.label_id')
            ->join('project_user', 'label_project.project_id', '=', 'project_user.project_id')
            ->join('projects', 'label_project.project_id', '=', 'projects.id')
            ->where(function ($query) {
                $query->orWhere('labels.author_id', $this->request->user()->id);
                $query->orWhere('projects.author_id', $this->request->user()->id);
                $query->orwhere('project_user.user_id', $this->request->user()->id);
            })
            ->orderBy('id');
    }


    /**
     * @param string $email
     * @return Builder
     */
    public
    function user_email(string $email): Builder
    {
        return $this->baseRequest()
            ->join('users', 'labels.author_id', '=', 'users.id')
            ->where('users.email', $email);
    }


    /**
     * @param string $name
     * @return Builder
     */
    public
    function projects(string $name): Builder
    {
        return $this->baseRequest()
            ->where('projects.name', $name);
    }
}
