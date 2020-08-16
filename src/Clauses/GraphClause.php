<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class GraphClause extends Clause
{
    protected $graphName;

    public function __construct($graphName)
    {
        $this->graphName = $graphName;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->graphName = $queryBuilder->normalizeArgument($this->graphName, 'Graph');

        return 'GRAPH ' . $this->graphName->compile($queryBuilder);
    }
}
