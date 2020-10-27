<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class SearchClause extends FilterClause
{
    protected $predicates = [];

    protected $defaultLogicalOperator = 'AND';

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->predicates = $queryBuilder->normalizePredicates($this->predicates);

        $compiledPredicates = $queryBuilder->compilePredicates($this->predicates);

        return 'SEARCH ' . rtrim($compiledPredicates);
    }
}
