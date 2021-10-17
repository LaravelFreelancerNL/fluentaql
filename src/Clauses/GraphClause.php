<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class GraphClause extends Clause
{
    protected string|QueryBuilder|Expression $graphName;

    public function __construct(string|QueryBuilder|Expression $graphName)
    {
        $this->graphName = $graphName;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->graphName = $queryBuilder->normalizeArgument($this->graphName, 'Graph');

        return 'GRAPH ' . $this->graphName->compile($queryBuilder);
    }
}
