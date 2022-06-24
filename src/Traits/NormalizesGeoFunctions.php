<?php

declare(strict_types=1);

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

    protected function normalizeDistance(QueryBuilder $queryBuilder): void
    {
        $this->normalizeNumbers($queryBuilder);
    }

    protected function normalizeGeoArea(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Object', 'Query', 'Reference', 'Bind']
        );

        $this->parameters[1] = $queryBuilder->normalizeArgument(
            $this->parameters[1],
            ['Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeGeoContains(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Object', 'Query', 'Reference', 'Bind']
        );

        $this->parameters[1] = $queryBuilder->normalizeArgument(
            $this->parameters[1],
            ['Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeGeoDistance(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Object', 'Query', 'Reference', 'Bind']
        );

        $this->parameters[1] = $queryBuilder->normalizeArgument(
            $this->parameters[1],
            ['Object', 'Query', 'Reference', 'Bind']
        );

        $this->parameters[2] = $queryBuilder->normalizeArgument(
            $this->parameters[2],
            ['Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeGeoEquals(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Object', 'Query', 'Reference', 'Bind']
        );

        $this->parameters[1] = $queryBuilder->normalizeArgument(
            $this->parameters[1],
            ['Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeGeoIntersects(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Object', 'Query', 'Reference', 'Bind']
        );

        $this->parameters[1] = $queryBuilder->normalizeArgument(
            $this->parameters[1],
            ['Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeGeoInRange(QueryBuilder $queryBuilder): void
    {
        $this->parameters['geoJsonA'] = $queryBuilder->normalizeArgument(
            $this->parameters['geoJsonA'],
            ['Object', 'Query', 'Reference', 'Bind']
        );

        $this->parameters['geoJsonB'] = $queryBuilder->normalizeArgument(
            $this->parameters['geoJsonB'],
            ['Object', 'Query', 'Reference', 'Bind']
        );

        $this->parameters['low'] = $queryBuilder->normalizeArgument(
            $this->parameters['low'],
            ['Number', 'Query', 'Reference', 'Bind']
        );

        $this->parameters['high'] = $queryBuilder->normalizeArgument(
            $this->parameters['high'],
            ['Number', 'Query', 'Reference', 'Bind']
        );

        if (isset($this->parameters['includeLow'])) {
            $this->parameters['includeLow'] = $queryBuilder->normalizeArgument(
                $this->parameters['includeLow'],
                ['Boolean', 'Query', 'Reference', 'Bind']
            );
        }

        if (isset($this->parameters['includeHigh'])) {
            $this->parameters['includeHigh'] = $queryBuilder->normalizeArgument(
                $this->parameters['includeHigh'],
                ['Boolean', 'Query', 'Reference', 'Bind']
            );
        }
    }

    protected function normalizeGeoLineString(QueryBuilder $queryBuilder): void
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeGeoMultiLineString(QueryBuilder $queryBuilder): void
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeGeoPoint(QueryBuilder $queryBuilder): void
    {
        $this->normalizeNumbers($queryBuilder);
    }

    protected function normalizeGeoMultiPoint(QueryBuilder $queryBuilder): void
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeGeoPolygon(QueryBuilder $queryBuilder): void
    {
        $this->normalizeArrays($queryBuilder);
    }

    protected function normalizeGeoMultiPolygon(QueryBuilder $queryBuilder): void
    {
        $this->normalizeArrays($queryBuilder);
    }
}
