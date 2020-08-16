<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class SortClause extends Clause
{
    protected $by;

    protected $direction;

    public function __construct($sortBy = null, $direction = null)
    {
        parent::__construct();

        $this->by = $sortBy;
        $this->direction = $direction;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $sortExpressions = [];

        //normalize string|null $by and $direction
        if (is_string($this->by) || $this->by == null) {
            $sortExpressions[] = $queryBuilder->normalizeSortExpression($this->by, $this->direction);
        }

        if (is_array($this->by)) {
            //Wandel door de array
            $sortExpressions = array_map(function ($expression) use ($queryBuilder) {
                return $queryBuilder->normalizeSortExpression($expression);
            }, $this->by);
        }

        $sortExpressions = implode(', ', array_map(function ($expression) {
            return implode(' ', $expression);
        }, $sortExpressions));

        return 'SORT ' . $sortExpressions;
    }
}
