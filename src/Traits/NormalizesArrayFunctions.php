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
    protected function normalizeAppend(QueryBuilder $queryBuilder)
    {
        $this->parameters['array'] = $queryBuilder->normalizeArgument(
            $this->parameters['array'],
            ['List', 'Query', 'Variable', 'Reference', 'Bind']
        );

        $this->parameters['values'] = $queryBuilder->normalizeArgument(
            $this->parameters['values'],
            ['List', 'Number', 'Query', 'Variable', 'Reference', 'Bind']
        );

        if (isset($this->parameters['unique'])) {
            $this->parameters['unique'] = $queryBuilder->normalizeArgument(
                $this->parameters['unique'],
                ['Boolean', 'Query', 'Variable', 'Reference', 'Bind']
            );
        }
    }

    protected function normalizeCountDistinct(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeFirst(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeFlatten(QueryBuilder $queryBuilder)
    {
        $this->parameters['array'] = $queryBuilder->normalizeArgument(
            $this->parameters['array'],
            ['List', 'Query', 'Variable', 'Reference', 'Bind']
        );

        $this->parameters['depth'] = $queryBuilder->normalizeArgument(
            $this->parameters['depth'],
            ['Number', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeLast(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeLength(QueryBuilder $queryBuilder)
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeShift(QueryBuilder $queryBuilder)
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['List', 'Query', 'Variable', 'Reference', 'Bind']
        );
    }

    protected function normalizeUnique(QueryBuilder $queryBuilder)
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['List', 'Query', 'Variable', 'Reference', 'Bind']
        );
    }
}
