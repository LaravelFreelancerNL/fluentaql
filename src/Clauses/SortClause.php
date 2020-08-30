<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class SortClause extends Clause
{
    protected $attributes;

    public function __construct($attributes)
    {
        parent::__construct();

        $this->attributes = $attributes;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        //Structure input of  reference + direction to the regular sort Expressions
        if (
            count($this->attributes) == 2
            && is_string($this->attributes[1])
            && $queryBuilder->grammar->isSortDirection($this->attributes[1])
        ) {
            $this->attributes = [[$this->attributes[0], $this->attributes[1]]];
        }

        if (empty($this->attributes)) {
            $this->attributes = [null];
        }

        $this->attributes = $this->normalizeSortExpressions($queryBuilder, $this->attributes);

        //Generate query output
        $sortExpressionOutput = array_map(function ($sortBy) use ($queryBuilder) {
            if ($sortBy instanceof ExpressionInterface) {
                return $sortBy->compile($queryBuilder);
            }

            $output = $sortBy[0]->compile($queryBuilder);
            if (isset($sortBy[1])) {
                $output .= ' ' . $sortBy[1];
            }
            return $output;
        }, $this->attributes);

        return 'SORT ' . implode(', ', $sortExpressionOutput);
    }

    protected function normalizeSortExpressions(QueryBuilder $queryBuilder, array $attributes)
    {
        return array_map(function ($sortBy) use ($queryBuilder) {
            if (is_string($sortBy) || $sortBy === null) {
                return $queryBuilder->normalizeArgument($sortBy, ['Null', 'Reference', 'Function', 'Bind']);
            }
            $sortBy[0] =  $queryBuilder->normalizeArgument($sortBy[0], ['Null', 'Reference', 'Function', 'Bind']);
            if (isset($sortBy[1]) && ! $queryBuilder->grammar->isSortDirection($sortBy[1])) {
                unset($sortBy[1]);
            }
            return $sortBy;
        }, $this->attributes);
    }
}
