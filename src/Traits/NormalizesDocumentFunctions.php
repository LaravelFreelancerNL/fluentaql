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
    protected function normalizeKeep(QueryBuilder $queryBuilder)
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

    abstract protected function normalizeNumbers(QueryBuilder $queryBuilder);

    protected function normalizeMatches(QueryBuilder $queryBuilder)
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

    protected function normalizeMerge(QueryBuilder $queryBuilder)
    {
        $this->normalizeDocuments($queryBuilder);
    }

    protected function normalizeParseIdentifier(QueryBuilder $queryBuilder)
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Query', 'Variable', 'Reference', 'Bind']
        );
    }

    protected function normalizeUnset(QueryBuilder $queryBuilder)
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
