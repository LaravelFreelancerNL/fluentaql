<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

trait NormalizesNumericFunctions
{
    abstract protected function normalizeArrays(QueryBuilder $queryBuilder);

    protected function normalizeAverage(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeCeil(QueryBuilder $queryBuilder)
    {
        $this->normalizeNumbers($queryBuilder);
    }

    protected function normalizeFloor(QueryBuilder $queryBuilder)
    {
        $this->normalizeNumbers($queryBuilder);
    }

    protected function normalizeMax(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeMin(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeProduct(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeRange(QueryBuilder $queryBuilder)
    {
        $this->normalizeNumbers($queryBuilder);
    }

    protected function normalizeRound(QueryBuilder $queryBuilder)
    {
        $this->normalizeNumbers($queryBuilder);
    }

    protected function normalizeSum(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }
}
