<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class OptionsClause extends Clause
{
    /**
     * @var mixed[]|object $options
     */
    protected array|object $options;

    /**
     * @param array<mixed>|object $options
     */
    public function __construct(array|object $options)
    {
        $this->options = $options;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->options = $queryBuilder->normalizeArgument($this->options, 'Object');

        return 'OPTIONS ' . $this->options->compile($queryBuilder);
    }
}
