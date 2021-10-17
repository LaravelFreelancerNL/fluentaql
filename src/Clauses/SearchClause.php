<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class SearchClause extends FilterClause
{
    /**
     * @var array<mixed>|PredicateExpression $predicates
     */
    protected array|PredicateExpression $predicates;

    protected string $defaultLogicalOperator = 'AND';

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->predicates = $queryBuilder->normalizePredicates($this->predicates);

        $compiledPredicates = $queryBuilder->compilePredicates($this->predicates);

        return 'SEARCH ' . rtrim($compiledPredicates);
    }
}
