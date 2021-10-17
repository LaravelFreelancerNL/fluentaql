<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class WithCountClause extends Clause
{
    protected string|QueryBuilder|Expression $countVariableName;

    public function __construct(
        string|QueryBuilder|Expression $countVariableName
    ) {
        $this->countVariableName = $countVariableName;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->countVariableName = $queryBuilder->normalizeArgument(
            $this->countVariableName,
            'Variable'
        );
        $queryBuilder->registerVariable($this->countVariableName);

        return 'WITH COUNT INTO ' . $this->countVariableName->compile($queryBuilder);
    }
}
