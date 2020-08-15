<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class WithCountClause extends Clause
{
    protected $countVariableName;

    public function __construct($countVariableName)
    {
        $this->countVariableName = $countVariableName;
    }

    public function compile(QueryBuilder $queryBuilder)
    {
        $this->countVariableName = $queryBuilder->normalizeArgument($this->countVariableName, 'Variable');
        $queryBuilder->registerVariable($this->countVariableName);

        return 'WITH COUNT INTO ' . $this->countVariableName->compile($queryBuilder);
    }
}
