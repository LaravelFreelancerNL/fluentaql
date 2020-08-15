<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class OptionsClause extends Clause
{
    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function compile(QueryBuilder $queryBuilder)
    {
        $this->options = $queryBuilder->normalizeArgument($this->options, 'Object');

        return 'OPTIONS ' . $this->options->compile($queryBuilder);
    }
}
