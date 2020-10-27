<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class FilterClause extends Clause
{
    protected $predicates = [];

    protected $defaultLogicalOperator = 'AND';

    /**
     * Filter statement.
     *
     * @param array $predicates
     */
    public function __construct(array $predicates)
    {
        parent::__construct();

        $this->predicates = $predicates;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->predicates = $queryBuilder->normalizePredicates($this->predicates);

        $compiledPredicates = $queryBuilder->compilePredicates($this->predicates);

        return 'FILTER ' . rtrim($compiledPredicates);
    }
}
