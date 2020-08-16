<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait NormalizesMiscellaneousFunctions
{
    protected function normalizeDocument(QueryBuilder $queryBuilder)
    {
        if ($this->parameters['id']  === null) {
            $this->parameters['id']  = $this->parameters['collection'];
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
            ['Id', 'Key', 'Query', 'List', 'Bind']
        );
    }
}
