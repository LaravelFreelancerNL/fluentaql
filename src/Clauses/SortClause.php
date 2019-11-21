<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class SortClause extends Clause
{
    protected $by;

    public function __construct($by)
    {
        parent::__construct();

        $this->by = $by;
    }

    public function compile()
    {
        $sortBy = implode(', ', array_map(function ($expression) {
            return implode(' ', $expression);
        }, $this->by));

        return 'SORT '.$sortBy;
    }
}
