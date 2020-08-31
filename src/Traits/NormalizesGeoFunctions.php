<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait NormalizesGeoFunctions
{
    abstract protected function normalizeNumbers(QueryBuilder $queryBuilder);

    protected function normalizeDistance(QueryBuilder $queryBuilder)
    {
        $this->normalizeNumbers($queryBuilder);
    }
}
