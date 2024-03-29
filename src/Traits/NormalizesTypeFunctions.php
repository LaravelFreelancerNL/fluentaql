<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

trait NormalizesTypeFunctions
{
    abstract protected function normalizeNumbers(QueryBuilder $queryBuilder);

    protected function normalizeToArray(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Boolean', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeToBool(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Boolean', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeToNumber(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Boolean', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeToString(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Number', 'Boolean', 'Query', 'Reference', 'Bind']
        );
    }
}
