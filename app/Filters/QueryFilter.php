<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter implements BaseRequestFilterInterface
{
    protected Request $request;
    protected Builder $builder;
    protected $delimiter = ',';

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function filters(): array
    {
        return $this->request->query();
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        if (!count($this->filters())) {
            call_user_func_array([$this, 'baseRequest'], array_filter([]));
        } else {
            foreach ($this->filters() as $name => $value) {
                if (method_exists($this, $name)) {
                    call_user_func_array([$this, $name], array_filter([$value]));
                }
            }
        }

        return $this->builder;
    }

    /**
     * @param $param
     * @return array
     */
    protected function paramToArray($param): array
    {
        return explode($this->delimiter, $param);
    }

    public function baseRequest(): Builder
    {
        return $this->builder;
    }
}
