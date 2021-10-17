<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait NormalizesDocumentFunctions
{
    abstract protected function normalizeNumbers(QueryBuilder $queryBuilder);

    protected function normalizeAttributes(QueryBuilder $queryBuilder): void
    {
        $this->parameters['document'] = $queryBuilder->normalizeArgument(
            $this->parameters['document'],
            ['Object', 'Query', 'Variable', 'Reference', 'Bind']
        );

        $this->parameters['removeInternal'] = $queryBuilder->normalizeArgument(
            $this->parameters['removeInternal'],
            ['Boolean', 'Query', 'Reference', 'Bind']
        );

        $this->parameters['sort'] = $queryBuilder->normalizeArgument(
            $this->parameters['sort'],
            ['Boolean', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeKeep(QueryBuilder $queryBuilder): void
    {
        $this->parameters['document'] = $queryBuilder->normalizeArgument(
            $this->parameters['document'],
            ['Object', 'Query', 'Variable', 'Reference', 'Bind']
        );

        $this->parameters['attributes'] = $queryBuilder->normalizeArgument(
            $this->parameters['attributes'],
            ['List', 'Query', 'Variable', 'Reference', 'Bind']
        );
    }

    protected function normalizeMatches(QueryBuilder $queryBuilder): void
    {
        $this->parameters['document'] = $queryBuilder->normalizeArgument(
            $this->parameters['document'],
            ['Object', 'Query', 'Variable', 'Reference', 'Bind']
        );

        $this->parameters['examples'] = $queryBuilder->normalizeArgument(
            $this->parameters['examples'],
            ['List', 'Object', 'Query', 'Variable', 'Reference', 'Bind']
        );

        $this->parameters['returnIndex'] = $queryBuilder->normalizeArgument(
            $this->parameters['returnIndex'],
            ['Boolean', 'Query', 'Variable', 'Reference', 'Bind']
        );
    }

    protected function normalizeMerge(QueryBuilder $queryBuilder): void
    {
        $this->normalizeDocuments($queryBuilder);
    }

    protected function normalizeParseIdentifier(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Query', 'Variable', 'Reference', 'Bind']
        );
    }

    protected function normalizeUnset(QueryBuilder $queryBuilder): void
    {
        $this->parameters['document'] = $queryBuilder->normalizeArgument(
            $this->parameters['document'],
            ['Object', 'Query', 'Variable', 'Reference', 'Bind']
        );

        $this->parameters['attributes'] = $queryBuilder->normalizeArgument(
            $this->parameters['attributes'],
            ['List', 'Query', 'Variable', 'Reference', 'Bind']
        );
    }
}
