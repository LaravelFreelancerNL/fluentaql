<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class PruneClause extends FilterClause
{
    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->predicates = $queryBuilder->normalizePredicates($this->predicates);

        $compiledPredicates = $this->compilePredicates($queryBuilder, $this->predicates);

        return 'PRUNE ' . rtrim($compiledPredicates);
    }
}
