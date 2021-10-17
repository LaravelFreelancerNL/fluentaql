<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

trait NormalizesNumericFunctions
{
    abstract protected function normalizeArrays(QueryBuilder $queryBuilder);

    protected function normalizeAverage(QueryBuilder $queryBuilder): void
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeCeil(QueryBuilder $queryBuilder): void
    {
        $this->normalizeNumbers($queryBuilder);
    }

    protected function normalizeFloor(QueryBuilder $queryBuilder): void
    {
        $this->normalizeNumbers($queryBuilder);
    }

    protected function normalizeMax(QueryBuilder $queryBuilder): void
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeMin(QueryBuilder $queryBuilder): void
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeProduct(QueryBuilder $queryBuilder): void
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeRange(QueryBuilder $queryBuilder): void
    {
        $this->normalizeNumbers($queryBuilder);
    }

    protected function normalizeRound(QueryBuilder $queryBuilder): void
    {
        $this->normalizeNumbers($queryBuilder);
    }

    protected function normalizeSum(QueryBuilder $queryBuilder): void
    {
        $this->normalizeArrays($queryBuilder);
    }
}
