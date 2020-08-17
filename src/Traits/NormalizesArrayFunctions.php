<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait NormalizesArrayFunctions
{
    protected function normalizeFirst(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeLast(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeLength(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeCountDistinct(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }
}
