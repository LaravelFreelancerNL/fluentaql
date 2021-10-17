<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait NormalizesDateFunctions
{
    protected function normalizeDateCompare(QueryBuilder $queryBuilder): void
    {
        $this->parameters['date1'] = $queryBuilder->normalizeArgument(
            $this->parameters['date1'],
            ['Number', 'Query', 'Reference', 'Bind']
        );

        $this->parameters['date2'] = $queryBuilder->normalizeArgument(
            $this->parameters['date2'],
            ['Number', 'Query', 'Reference', 'Bind']
        );

        $this->parameters['unitRangeStart'] = $queryBuilder->normalizeArgument(
            $this->parameters['unitRangeStart'],
            ['Query', 'Reference', 'Bind']
        );

        if (isset($this->parameters['unitRangeEnd'])) {
            $this->parameters['unitRangeEnd'] = $queryBuilder->normalizeArgument(
                $this->parameters['unitRangeEnd'],
                ['Query', 'Reference', 'Bind']
            );
        }
    }

    protected function normalizeDateDay(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Function', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeDateFormat(QueryBuilder $queryBuilder): void
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

    protected function normalizeDateHour(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Function', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeDateIso8601(QueryBuilder $queryBuilder): void
    {
        $this->normalizeNumbers($queryBuilder);
    }

    protected function normalizeDateMillisecond(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Function', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeDateMinute(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Function', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeDateMonth(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Function', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeDateSecond(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Function', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeDateTimestamp(QueryBuilder $queryBuilder): void
    {
        $this->normalizeNumbers($queryBuilder);
    }

    protected function normalizeDateUtcToLocal(QueryBuilder $queryBuilder): void
    {
        $this->parameters['date'] = $queryBuilder->normalizeArgument(
            $this->parameters['date'],
            ['Number', 'Query', 'Reference', 'Bind']
        );

        $this->parameters['timezone'] = $queryBuilder->normalizeArgument(
            $this->parameters['timezone'],
            ['Query', 'Reference', 'Bind']
        );

        if (isset($this->parameters['zoneInfo'])) {
            $this->parameters['zoneInfo'] = $queryBuilder->normalizeArgument(
                $this->parameters['zoneInfo'],
                ['Object', 'Query', 'Reference', 'Bind']
            );
        }
    }


    protected function normalizeDateLocalToUtc(QueryBuilder $queryBuilder): void
    {
        $this->parameters['date'] = $queryBuilder->normalizeArgument(
            $this->parameters['date'],
            ['Number', 'Query', 'Reference', 'Bind']
        );

        $this->parameters['timezone'] = $queryBuilder->normalizeArgument(
            $this->parameters['timezone'],
            ['Query', 'Reference', 'Bind']
        );

        if (isset($this->parameters['zoneInfo'])) {
            $this->parameters['zoneInfo'] = $queryBuilder->normalizeArgument(
                $this->parameters['zoneInfo'],
                ['Object', 'Query', 'Reference', 'Bind']
            );
        }
    }

    protected function normalizeDateTrunc(QueryBuilder $queryBuilder): void
    {
        $this->parameters['date'] = $queryBuilder->normalizeArgument(
            $this->parameters['date'],
            ['Number', 'Query', 'Reference', 'Bind']
        );

        $this->parameters['unit'] = $queryBuilder->normalizeArgument(
            $this->parameters['unit'],
            ['Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeDateRound(QueryBuilder $queryBuilder): void
    {
        $this->parameters['date'] = $queryBuilder->normalizeArgument(
            $this->parameters['date'],
            ['Number', 'Query', 'Reference', 'Bind']
        );

        $this->parameters['amount'] = $queryBuilder->normalizeArgument(
            $this->parameters['amount'],
            ['Number', 'Query', 'Reference', 'Bind']
        );

        $this->parameters['unit'] = $queryBuilder->normalizeArgument(
            $this->parameters['unit'],
            ['Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeDateYear(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Function', 'Query', 'Reference', 'Bind']
        );
    }
}
