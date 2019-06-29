<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    protected $request, $builder;

    protected $filters = [];

    /**
     * Filters constructor
     * 
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        //We apply our filters to the builder

        $this->builder = $builder;

        // dd($this->getFilters());

        // Just trying a fucntional approach
        //
        // collect($this->getFilters())
        //     ->filter(fucntion ($value, $filter) {
        //         return method_exists($this, $filter);
        //     })
        //     ->each(function ($value, $filter) {
        //         $this->$filter($value);
        //     });

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
        
    }

    public function getFilters()
    {
        $filters = array_intersect(array_keys($this->request->all()), $this->filters);

        return $this->request->only($filters);
    }
}