<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class AggregateClause extends Clause
{
    protected $variableName;
    protected $aggregateExpression;

    public function __construct($variableName, $aggregateExpression)
    {
        $this->variableName = $variableName;

        $this->aggregateExpression = $aggregateExpression;
    }

    public function compile(QueryBuilder $queryBuilder)
    {
        $this->variableName = $queryBuilder->normalizeArgument($this->variableName, 'Variable');
        $queryBuilder->registerVariable($this->variableName);

        $this->aggregateExpression = $queryBuilder->normalizeArgument(
            $this->aggregateExpression,
            ['Reference', 'Function', 'Query', 'Bind']
        );

        return "AGGREGATE {$this->variableName->compile($queryBuilder)} = {$this->aggregateExpression->compile($queryBuilder)}";
    }
}
