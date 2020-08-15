<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class KeepClause extends Clause
{
    protected $keepVariable;

    public function __construct($keepVariable)
    {
        $this->keepVariable = $keepVariable;
    }

    public function compile(QueryBuilder $queryBuilder)
    {
        $this->keepVariable = $queryBuilder->normalizeArgument($this->keepVariable, 'Variable');
        $queryBuilder->registerVariable($this->keepVariable);


        return 'KEEP ' . $this->keepVariable->compile($queryBuilder);
    }
}
