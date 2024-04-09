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
trait NormalizesMiscellaneousFunctions
{
    protected function normalizeAssert(QueryBuilder $queryBuilder): void
    {
        if (
            !is_array($this->parameters['predicates'])
            && !$this->parameters['predicates'] instanceof PredicateExpression
        ) {
            $this->parameters['predicates'] = [$this->parameters['predicates']];
        }
        $this->parameters['predicates'] = $queryBuilder->normalizePredicates($this->parameters['predicates']);
        $this->parameters['errorMessage'] = $queryBuilder->normalizeArgument(
            $this->parameters['errorMessage'],
            ['Reference', 'Query', 'Bind']
        );
    }

    protected function normalizeDocument(QueryBuilder $queryBuilder): void
    {
        if ($this->parameters['id'] === null) {
            $this->parameters['id'] = $this->parameters['collection'];
            unset($this->parameters['collection']);
        }

        if (isset($this->parameters['collection'])) {
            $this->parameters['collection'] = $queryBuilder->normalizeArgument(
                $this->parameters['collection'],
                ['Collection', 'Id', 'Query', 'Bind']
            );
        }
        $this->parameters['id'] = $queryBuilder->normalizeArgument(
            $this->parameters['id'],
            ['RegisteredVariable', 'Reference', 'Id', 'Key', 'Query', 'List', 'Bind']
        );
    }

    protected function normalizeFirstDocument(QueryBuilder $queryBuilder): void
    {
        $this->normalizeAny($queryBuilder);
    }

    protected function normalizeWarn(QueryBuilder $queryBuilder): void
    {
        $this->normalizeAssert($queryBuilder);
    }
}
