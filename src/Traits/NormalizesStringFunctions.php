<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait NormalizesStringFunctions
{
    abstract protected function normalizeNumbers(QueryBuilder $queryBuilder);

    protected function normalizeConcat(QueryBuilder $queryBuilder)
    {
        $this->normalizeStrings($queryBuilder);
    }
}
