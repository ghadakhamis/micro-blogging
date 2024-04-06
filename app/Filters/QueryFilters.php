<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QueryFilters
{
    protected $request;
    protected $builder;
    protected $filters = [];

    protected $keys    = [];

    protected $orders = ['asc', 'desc'];
  
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
  
    public function apply(Builder $builder)
    {
        $this->builder = $builder;
        foreach ($this->filters() as $method => $value) {
            if ( ! method_exists($this, $method)) {
                continue;
            }
            $this->$method($value);
        }

        return $this->builder;
    }
  
    public function filters()
    {
        return array_filter($this->request->only($this->filters));
    }

    /**
     * Sort the services by the given order and field.
     *
     * @param string $value
     */
    public function sort(string $value)
    {
        $sortData = explode(',', $value);
        $field = in_array($sortData[0], $this->keys)? $sortData[0] : 'created_at';
        $order = isset($sortData[1]) && in_array($sortData[1], $this->orders)? $sortData[1] : 'DESC';
        $this->builder->orderBy($field, $order);
    }  
}