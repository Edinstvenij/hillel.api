<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class UsersFilter extends QueryFilter
{
    /**
     * @param string $name
     * @return Builder
     */
    public function name(string $name): Builder
    {
        return $this->builder->where('name', $name);
    }

    /**
     * @param string $email
     * @return Builder
     */
    public function email(string $email): Builder
    {
        return $this->builder->where('email', $email);
    }

    /**
     * @param string $date
     * @return Builder
     */
    public function verified(string $date): Builder
    {
        return $this->builder->where('verified_at', $date);
    }

    /**
     * @param int $countryId
     * @return Builder
     */
    public function country(int $countryId): Builder
    {
        return $this->builder->where('country_id', $countryId);
    }
}
