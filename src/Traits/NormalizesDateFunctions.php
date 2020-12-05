<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait NormalizesDateFunctions
{
    protected function normalizeDateIso8601(QueryBuilder $queryBuilder)
    {
        $this->normalizeNumbers($queryBuilder);
    }

    protected function normalizeDateTimestamp(QueryBuilder $queryBuilder)
    {
        $this->normalizeNumbers($queryBuilder);
    }

    protected function normalizeDateYear(QueryBuilder $queryBuilder)
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Function', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeDateMonth(QueryBuilder $queryBuilder)
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Function', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeDateDay(QueryBuilder $queryBuilder)
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Function', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeDateHour(QueryBuilder $queryBuilder)
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Function', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeDateMinute(QueryBuilder $queryBuilder)
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Function', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeDateSecond(QueryBuilder $queryBuilder)
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Function', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeDateMillisecond(QueryBuilder $queryBuilder)
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Function', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeDateFormat(QueryBuilder $queryBuilder)
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Function', 'Query', 'Reference', 'Bind']
        );

        $this->parameters[1] = $queryBuilder->normalizeArgument(
            $this->parameters[1],
            ['Function', 'Query', 'Reference', 'Bind']
        );
    }
}
