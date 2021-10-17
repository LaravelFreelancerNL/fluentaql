<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class KeepClause extends Clause
{
    protected string|QueryBuilder|Expression $keepVariable;

    public function __construct(string|QueryBuilder|Expression $keepVariable)
    {
        $this->keepVariable = $keepVariable;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->keepVariable = $queryBuilder->normalizeArgument($this->keepVariable, 'Variable');
        $queryBuilder->registerVariable($this->keepVariable);


        return 'KEEP ' . $this->keepVariable->compile($queryBuilder);
    }
}
