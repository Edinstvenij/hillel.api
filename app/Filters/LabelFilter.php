<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class LabelFilter extends QueryFilter
{
    /**
     * @param string $email
     * @return Builder
     */
    public function user_email(string $email): Builder
    {
        return $this->builder
            ->join('users', 'author_id', '=', 'users.id')
            ->where('users.email', $email);
    }

    /**
     * @param int $id
     * @return Builder
     */
    public function projects(int $id): Builder
    {
        return $this->builder
            ->join('label_project', 'id', '=', 'label_project.label_id')
            ->join('projects', 'projects.id', '=', 'label_project.project_id')
            ->where('projects.id', $id);
    }
}
