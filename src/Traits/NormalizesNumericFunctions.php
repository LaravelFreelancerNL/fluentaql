<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait NormalizesNumericFunctions
{
    protected function normalizeAverage(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeMax(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeMin(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeSum(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }
}
