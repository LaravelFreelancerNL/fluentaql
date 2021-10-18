<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait NormalizesArangoSearchFunctions
{
    protected function normalizeAnalyzer(QueryBuilder $queryBuilder): void
    {
        if (
            ! is_array($this->parameters['predicates'])
            && ! $this->parameters['predicates'] instanceof PredicateExpression
        ) {
            $this->parameters['predicates'] = [$this->parameters['predicates']];
        }
        $this->parameters['predicates'] = $queryBuilder->normalizePredicates($this->parameters['predicates']);
        $this->parameters['analyzer'] = $queryBuilder->normalizeArgument(
            $this->parameters['analyzer'],
            ['Reference', 'Query', 'Bind']
        );
    }

    /**
     * @psalm-suppress MixedArgument
     */
    protected function normalizeBoost(QueryBuilder $queryBuilder): void
    {
        if (
            ! is_array($this->parameters['predicates'])
            && ! $this->parameters['predicates'] instanceof PredicateExpression
        ) {
            $this->parameters['predicates'] = [$this->parameters['predicates']];
        }
        $this->parameters['predicates'] = $queryBuilder->normalizePredicates($this->parameters['predicates']);

        $this->parameters['boost'] = $queryBuilder->normalizeArgument(
            $this->parameters['boost'],
            ['Number', 'Reference', 'Query', 'Bind']
        );
    }

    protected function normalizeBm25(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Query', 'Reference']
        );
        if (isset($this->parameters[1])) {
            $this->parameters[1] = $queryBuilder->normalizeArgument(
                $this->parameters[1],
                ['Number', 'Query', 'Reference', 'Bind']
            );
        }
        if (isset($this->parameters[2])) {
            $this->parameters[2] = $queryBuilder->normalizeArgument(
                $this->parameters[2],
                ['Number', 'Query', 'Reference', 'Bind']
            );
        }
    }

    protected function normalizeTfidf(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Query', 'Reference']
        );
        if (isset($this->parameters[1])) {
            $this->parameters[1] = $queryBuilder->normalizeArgument(
                $this->parameters[1],
                ['Boolean', 'Query', 'Reference', 'Bind']
            );
        }
    }

    protected function normalizeExists(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Query', 'Reference']
        );
        if (isset($this->parameters[1])) {
            $this->parameters[1] = $queryBuilder->normalizeArgument(
                $this->parameters[1],
                ['Query', 'Reference', 'Bind']
            );
        }
    }

    protected function normalizeInRange(QueryBuilder $queryBuilder): void
    {
        $this->parameters['path'] = $queryBuilder->normalizeArgument(
            $this->parameters['path'],
            ['Query', 'Reference']
        );
        if (isset($this->parameters['low'])) {
            $this->parameters['low'] = $queryBuilder->normalizeArgument(
                $this->parameters['low'],
                ['Number', 'Boolean', 'Null', 'Query', 'Reference', 'Bind']
            );
        }
        if (isset($this->parameters['high'])) {
            $this->parameters['high'] = $queryBuilder->normalizeArgument(
                $this->parameters['high'],
                ['Number', 'Boolean', 'Null', 'Query', 'Reference', 'Bind']
            );
        }
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

    protected function normalizeLevenshteinMatch(QueryBuilder $queryBuilder): void
    {
        $this->parameters['path'] = $queryBuilder->normalizeArgument(
            $this->parameters['path'],
            ['Query', 'Reference']
        );
        $this->parameters['target'] = $queryBuilder->normalizeArgument(
            $this->parameters['target'],
            ['Query', 'Reference', 'Bind']
        );
        $this->parameters['distance'] = $queryBuilder->normalizeArgument(
            $this->parameters['distance'],
            ['Number', 'Reference', 'Bind']
        );

        if (isset($this->parameters['transpositions'])) {
            $this->parameters['transpositions'] = $queryBuilder->normalizeArgument(
                $this->parameters['transpositions'],
                ['Boolean', 'Query', 'Reference', 'Bind']
            );
        }
        if (isset($this->parameters['maxTerms'])) {
            $this->parameters['maxTerms'] = $queryBuilder->normalizeArgument(
                $this->parameters['maxTerms'],
                ['Number', 'Query', 'Reference', 'Bind']
            );
        }
        if (isset($this->parameters['prefix'])) {
            $this->parameters['prefix'] = $queryBuilder->normalizeArgument(
                $this->parameters['prefix'],
                ['Query', 'Reference', 'Bind']
            );
        }
    }

    protected function normalizeLike(QueryBuilder $queryBuilder): void
    {
        $this->parameters['path'] = $queryBuilder->normalizeArgument(
            $this->parameters['path'],
            ['Reference', 'Query']
        );
        $this->parameters['search'] = $queryBuilder->normalizeArgument(
            $this->parameters['search'],
            ['Reference', 'Query', 'Bind']
        );
    }

    protected function normalizeNgramMatch(QueryBuilder $queryBuilder): void
    {
        $this->parameters['path'] = $queryBuilder->normalizeArgument(
            $this->parameters['path'],
            ['Query', 'Reference']
        );
        $this->parameters['target'] = $queryBuilder->normalizeArgument(
            $this->parameters['target'],
            ['Query', 'Reference', 'Bind']
        );

        if (isset($this->parameters['threshold'])) {
            $this->parameters['threshold'] = $queryBuilder->normalizeArgument(
                $this->parameters['threshold'],
                ['Number', 'Reference', 'Bind']
            );
        }

        if (isset($this->parameters['analyzer'])) {
            $this->parameters['analyzer'] = $queryBuilder->normalizeArgument(
                $this->parameters['analyzer'],
                ['Query', 'Reference', 'Bind']
            );
        }
    }

    protected function normalizePhrase(QueryBuilder $queryBuilder): void
    {
        /** @var mixed $parameter */
        foreach ($this->parameters as $key => $parameter) {
            if ($key === 0) {
                $this->parameters[$key] = $queryBuilder->normalizeArgument(
                    $parameter,
                    ['Reference', 'Query', 'Bind']
                );

                continue;
            }
            $this->parameters[$key] = $queryBuilder->normalizeArgument(
                $parameter,
                ['Number', 'Reference', 'Query', 'Bind']
            );
        }
    }
}
