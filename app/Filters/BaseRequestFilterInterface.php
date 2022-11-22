<?php

namespace App\Filters;

interface BaseRequestFilterInterface
{
    public function baseRequest(): \Illuminate\Database\Eloquent\Builder;
}
